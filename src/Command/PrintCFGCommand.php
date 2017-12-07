<?php

namespace PHPSA\Command;

use FilesystemIterator;
use PhpParser\ParserFactory;
use PHPSA\Application;
use PHPSA\Compiler;
use PHPSA\Context;
use PHPSA\ControlFlow\BlockTraverser;
use PHPSA\ControlFlow\Visitor;
use PHPSA\Definition\FileParser;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Webiny\Component\EventManager\EventManager;

/**
 * Command to dump the analyzer documentation as markdown
 */
class PrintCFGCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('print-cfg')
            ->setDescription('Dumps Control Flow Graph')
            ->addOption('config-file', null, InputOption::VALUE_REQUIRED, 'Path to the configuration file.')
            ->addArgument('path', InputArgument::OPTIONAL, 'Path to check file or directory', '.');
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');

        if (extension_loaded('xdebug')) {
            /**
             * This will disable only showing stack traces on error conditions.
             */
            if (function_exists('xdebug_disable')) {
                xdebug_disable();
            }

            $output->writeln('<error>It is highly recommended to disable the XDebug extension before invoking this command.</error>');
        }

        /** @var Application $application */
        $application = $this->getApplication();
        $application->compiler = new Compiler();

        $configFile = $input->getOption('config-file') ?: '.phpsa.yml';
        $configDir = realpath($input->getArgument('path'));
        $application->configuration = $this->loadConfiguration($configFile, $configDir);

        $parser = $this->createParser($application);

        $output->writeln('Used config file: ' . $application->configuration->getPath());

        $em = EventManager::getInstance();
        $context = new Context($output, $application, $em);

        $fileParser = new FileParser(
            $parser,
            $application->compiler
        );

        $path = $input->getArgument('path');
        if (is_dir($path)) {
            $directoryIterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
            );
            $output->writeln('Scanning directory <info>' . $path . '</info>');

            $count = 0;

            /** @var SplFileInfo $file */
            foreach ($directoryIterator as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $context->debug($file->getPathname());
                $count++;
            }

            $output->writeln("Found <info>{$count} files</info>");

            if ($count > 100) {
                $output->writeln('<comment>Caution: You are trying to scan a lot of files; this might be slow. For bigger libraries, consider setting up a dedicated platform or using ci.lowl.io.</comment>');
            }

            $output->writeln('');

            /** @var SplFileInfo $file */
            foreach ($directoryIterator as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $fileParser->parserFile($file->getPathname(), $context);
            }
        } elseif (is_file($path)) {
            $fileParser->parserFile($path, $context);
        }


        /**
         * Step 2 Recursive check ...
         */
        $application->compiler->compile($context);

        $traverser = new BlockTraverser();
        //$traverser->addVisitor(new Visitor\DebugTextVisitor());
        $traverser->addVisitor(new Visitor\UnreachableVisitor());

        $printer = new \PHPSA\ControlFlow\Printer\DebugText();

        $functions = $application->compiler->getFunctions();
        foreach ($functions as $function) {
            $output->writeln('Function: ' . $function->getName());

            $cfg = $function->getCFG();
            if ($cfg) {
                $traverser->traverse($cfg);
                $printer->printGraph($cfg->getRoot());
            }
        }

        $classess = $application->compiler->getClasses();
        foreach ($classess as $class) {
            $output->writeln('Class: ' . $class->getName());

            $methods = $class->getMethods();

            foreach ($methods as $method) {
                $output->writeln('Method: ' . $method->getName());

                $cfg = $method->getCFG();
                if ($cfg) {
                    $traverser->traverse($cfg);
                    $printer->printGraph($cfg->getRoot());
                }
            }
        }

        $output->writeln('');
        $output->writeln('Memory usage: ' . $this->getMemoryUsage(false) . ' (peak: ' . $this->getMemoryUsage(true) . ') MB');
    }
}
