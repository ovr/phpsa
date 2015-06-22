<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Command;

use CallbackFilterIterator;
use FilesystemIterator;
use PhpParser\Parser;
use PHPSA\Context;
use PHPSA\Definition\ClassDefinition;
use PHPSA\Definition\ClassMethod;
use PhpParser\Node;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('check')
            ->setDescription('SPA')
            ->setDefinition(array(
                new InputArgument('path', InputArgument::OPTIONAL, 'Path to check files', '.'),
            ));
    }

    /**
     * @var ClassDefinition[]
     */
    protected $classes = array();

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');

        $lexer = new \PhpParser\Lexer(array(
            'usedAttributes' => array(
                'comments',
                'startLine',
                'endLine',
                'startTokenPos',
                'endTokenPos'
            )
        ));


        $parser = new Parser(new \PhpParser\Lexer\Emulative);

        $path = $input->getArgument('path');

        $context = new Context();
        $context->output = $output;

        if (is_dir($path)) {
            $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS));
            $it = new CallbackFilterIterator($it, function (SplFileInfo $file) {
                return $file->getExtension() == 'php';
            });

            /** @var SplFileInfo $file */
            foreach ($it as $file) {
                $this->parserFile($file->getPathname(), $parser, $context);
            }
        } elseif (is_file($path)) {
            $this->parserFile($path, $parser, $context);
        }


        /**
         * Step 2 Recursive check ...
         */

        /**
         * @var $class ClassDefinition
         */
        foreach ($this->classes as $class) {
            $context->scope = $class;

            $class->compile($context);
        }

        $output->writeln('');
    }

    /**
     * @param string $filepath
     * @param Parser $parser
     * @param Context $context
     */
    protected function parserFile($filepath, Parser $parser, Context $context)
    {
        try {
            $code = file_get_contents($filepath);
            $stmts = $parser->parse($code);

            /**
             * Step 1 Precompile
             */

            foreach ($stmts as $st) {
                if ($st instanceof Node\Stmt\Class_) {
                    $classDefintion = new ClassDefinition($st->name);
                    $classDefintion->setFilepath($filepath);

                    foreach ($st->stmts as $st) {
                        if ($st instanceof Node\Stmt\ClassMethod) {
                            $method = new ClassMethod($st->name, $st->stmts, $st->type);

                            $classDefintion->addMethod($method);
                        } elseif ($st instanceof Node\Stmt\Property) {
                            $classDefintion->addProperty($st);
                        } elseif ($st instanceof Node\Stmt\ClassConst) {
                            $classDefintion->addConst($st);
                        }
                    }

                    $this->classes[] = $classDefintion;
                }
            }

            $context->application = $this->getApplication();
            $context->clear();
        } catch (\PhpParser\Error $e) {
            $context->sytaxError($e, $filepath);
        }
    }
}
