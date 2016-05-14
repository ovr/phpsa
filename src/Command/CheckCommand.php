<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Command;

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PHPSA\AliasManager;
use PHPSA\Application;
use PHPSA\Compiler;
use PHPSA\Context;
use PHPSA\Definition\ClassDefinition;
use PHPSA\Definition\ClassMethod;
use PHPSA\Definition\FunctionDefinition;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;
use Exception;
use FilesystemIterator;
use PhpParser\Node;
use PhpParser\Parser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Webiny\Component\EventManager\EventManager;

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
            ->addOption('blame', null, InputOption::VALUE_OPTIONAL, 'Git blame author for bad code ;)', false)
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

        $em = EventManager::getInstance();
        $context = new Context($output, $application, $em);

        /**
         * Store option's in application's configuration
         */
        $application->getConfiguration()->setValue('blame', $input->getOption('blame'));

        $astTraverser = new \PhpParser\NodeTraverser();
        $astTraverser->addVisitor(new \PhpParser\NodeVisitor\NameResolver);

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

                $this->parserFile($file->getPathname(), $parser, $astTraverser, $context);
            }
        } elseif (is_file($path)) {
            $this->parserFile($path, $parser, $astTraverser, $context);
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

    /**
     * @param string $filepath
     * @param Parser $parser
     * @param NodeTraverser $nodeTraverser
     * @param Context $context
     */
    protected function parserFile($filepath, Parser $parser, NodeTraverser $nodeTraverser, Context $context)
    {
        $context->setFilepath($filepath);

        try {
            if (!is_readable($filepath)) {
                throw new RuntimeException('File ' . $filepath . ' is not readable');
            }

            $context->debug('<comment>Precompile: ' . $filepath . '.</comment>');

            $code = file_get_contents($filepath);
            $astTree = $parser->parse($code);

            $nodeTraverser->traverse($astTree);

            $context->aliasManager = new AliasManager();
            $namespace = null;

            /**
             * Step 1 Precompile
             */
            foreach ($astTree as $topStatement) {
                if ($topStatement instanceof Node\Stmt\Namespace_) {
                    /**
                     * Namespace block can be created without NS name
                     */
                    if ($topStatement->name) {
                        $namespace = $topStatement->name->toString();
                        $context->aliasManager->setNamespace($namespace);
                    }

                    if ($topStatement->stmts) {
                        $this->parseTopDefinitions($topStatement->stmts, $context->aliasManager, $filepath);
                    }
                } else {
                    $this->parseTopDefinitions($topStatement, $context->aliasManager, $filepath);
                }
            }
            
            $context->clear();
        } catch (\PhpParser\Error $e) {
            $context->sytaxError($e, $filepath);
        } catch (Exception $e) {
            $context->output->writeln("<error>{$e->getMessage()}</error>");
        }
    }

    /**
     * @param Node\Stmt $topStatement
     * @param AliasManager $aliasManager
     * @param string $filepath
     */
    protected function parseTopDefinitions($topStatement, AliasManager $aliasManager, $filepath)
    {
        foreach ($topStatement as $statement) {
            if ($statement instanceof Node\Stmt\Use_) {
                if (count($statement->uses) > 0) {
                    foreach ($statement->uses as $use) {
                        $aliasManager->add($use->name->parts);
                    }
                }
            } elseif ($statement instanceof Node\Stmt\Class_) {
                $definition = new ClassDefinition($statement->name, $statement->type);
                $definition->setFilepath($filepath);
                $definition->setNamespace($aliasManager->getNamespace());

                if ($statement->extends) {
                    $definition->setExtendsClass($statement->extends->toString());
                }

                if ($statement->implements) {
                    foreach ($statement->implements as $interface) {
                        $definition->addInterface($interface->toString());
                    }
                }

                foreach ($statement->stmts as $stmt) {
                    if ($stmt instanceof Node\Stmt\ClassMethod) {
                        $method = new ClassMethod($stmt->name, $stmt, $stmt->type);

                        $definition->addMethod($method);
                    } elseif ($stmt instanceof Node\Stmt\Property) {
                        $definition->addProperty($stmt);
                    } elseif ($stmt instanceof Node\Stmt\ClassConst) {
                        $definition->addConst($stmt);
                    }
                }

                $this->getCompiler()->addClass($definition);
            } elseif ($statement instanceof Node\Stmt\Function_) {
                $definition = new FunctionDefinition($statement->name, $statement);
                $definition->setFilepath($filepath);
                $definition->setNamespace($aliasManager->getNamespace());

                $this->getCompiler()->addFunction($definition);
            }
        }
    }
}
