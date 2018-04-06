<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Command;

use PHPSA\Analyzer;
use PHPSA\Application;
use PHPSA\Compiler;
use PHPSA\Context;
use PHPSA\Definition\FileParser;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use FilesystemIterator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Webiny\Component\EventManager\EventManager;

/**
 * Command to run compiler and analyzers on files
 *
 * @package PHPSA\Command
 * @method Application getApplication();
 */
class CheckCommand extends AbstractCommand
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('check')
            ->setDescription('Runs compiler and analyzers on all files in path')
            ->addOption('config-file', null, InputOption::VALUE_REQUIRED, 'Path to the configuration file.')
            ->addArgument('path', InputArgument::OPTIONAL, 'Path to check file or directory', '.')
            ->addOption(
                'report-json',
                null,
                InputOption::VALUE_REQUIRED,
                'Path to save detailed report in JSON format. Example: /tmp/report.json'
            );
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
        Analyzer\Factory::factory($em, $application->configuration);
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

            $ignore =  $application->configuration->getValue('ignore');
            /** @var SplFileInfo $file */
            foreach ($directoryIterator as $file) {
                $skip = 0;
                foreach ($ignore as $item) {
                    $item = preg_replace('#/+#', '/', ($path . $item));

                    if (preg_match("#$item#", $file->getPathname())) {
                        $skip = 1;
                        break;
                    }
                }

                if ($file->getExtension() !== 'php' || $skip) {
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
                $skip = 0;
                foreach ($ignore as $item) {
                    $item = preg_replace('#/+#', '/', ($path . $item));

                    if (preg_match("#$item#", $file->getPathname())) {
                        $skip = 1;
                        break;
                    }
                }

                if ($file->getExtension() !== 'php' || $skip) {
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

        $jsonReport = $input->getOption('report-json');
        if ($jsonReport) {
            file_put_contents(
                $jsonReport,
                json_encode(
                    $this->getApplication()->getIssuesCollector()->getIssues()
                )
            );
        }

        $output->writeln('');
        $output->writeln('Memory usage: ' . $this->getMemoryUsage(false) . ' (peak: ' . $this->getMemoryUsage(true) . ') MB');
    }
}
