<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Command;

use PhpParser\ParserFactory;
use PHPSA\Analyzer;
use PHPSA\Analyzer\EventListener\ExpressionListener;
use PHPSA\Analyzer\EventListener\StatementListener;
use PHPSA\Application;
use PHPSA\Compiler;
use PHPSA\Configuration;
use PHPSA\ConfigurationLoader;
use PHPSA\Context;
use PHPSA\Definition\FileParser;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use FilesystemIterator;
use PhpParser\Node;
use PhpParser\Parser;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Webiny\Component\EventManager\EventManager;
use PHPSA\Analyzer\Pass as AnalyzerPass;

/**
 * Class CheckCommand
 * @package PHPSA\Command
 *
 * @method Application getApplication();
 */
class CheckCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('check')
            ->setDescription('SPA')
            ->addOption('blame', null, InputOption::VALUE_OPTIONAL, 'Git blame author for bad code ;)', -1)
            ->addArgument('path', InputArgument::OPTIONAL, 'Path to check file or directory', '.')
            ->addOption(
                'report-json',
                null,
                InputOption::VALUE_REQUIRED,
                'Path to save detailed report in JSON format. Example: /tmp/report.json'
            );
    }

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

        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7, new \PhpParser\Lexer\Emulative(
            array(
                'usedAttributes' => array(
                    'comments',
                    'startLine',
                    'endLine',
                    'startTokenPos',
                    'endTokenPos'
                )
            )
        ));

        /** @var Application $application */
        $application = $this->getApplication();
        $application->compiler = new Compiler();

        $loader = new ConfigurationLoader(
            new FileLocator(
                [
                    realpath($input->getArgument('path')) . DIRECTORY_SEPARATOR
                ]
            )
        );

        $application->configuration = new Configuration(
            $loader->load('.phpsa.yml')
        );

        $em = EventManager::getInstance();
        Analyzer\Factory::factory($em);
        
        $context = new Context($output, $application, $em);

        /**
         * Store option's in application's configuration
         */
        $blame = $input->getOption('blame');
        if ($blame === -1) {
            $application->configuration->setValue('blame', $blame);
        }

        $fileParser = new FileParser(
            $parser,
            $this->getCompiler()
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
                if ($file->getExtension() != 'php') {
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
                if ($file->getExtension() != 'php') {
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

    /**
     * @param boolean $type
     * @return float
     */
    protected function getMemoryUsage($type)
    {
        return round(memory_get_usage($type) / 1024 / 1024, 2);
    }

    /**
     * @return Compiler
     */
    protected function getCompiler()
    {
        return $this->getApplication()->compiler;
    }
}
