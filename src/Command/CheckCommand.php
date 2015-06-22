<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Command;

use PHPSA\Definition\ClassDefinition;
use PHPSA\Definition\ClassMethod;
use PhpParser\Node;

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


        $parser = new \PhpParser\Parser(new \PhpParser\Lexer\Emulative);

        $inputDir = $input->getArgument('path');

        $context = new \PHPSA\Context();
        $context->output = $output;

        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($inputDir, \FilesystemIterator::SKIP_DOTS));
        $it = new \CallbackFilterIterator($it, function (\SplFileInfo $file) {
            return $file->getExtension() == 'php';
        });

        /** @var \SplFileInfo $file */
        foreach ($it as $file) {
            $filepath = $file->getPathname();

            try {
                $code = file_get_contents($filepath);
                $stmts = $parser->parse($code);


                /**
                 * Step 1 Precompile
                 */

                /**
                 * @var ClassDefinition[]
                 */
                $classes = [];

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

                        $classes[] = $classDefintion;
                    }
                }

                $context->application = $this->getApplication();
                $context->clear();

                /**
                 * Step 2 Recursive check ...
                 */

                /**
                 * @var $class ClassDefinition
                 */
                foreach ($classes as $class) {
                    $context->scope = $class;

                    $class->compile($context);
                }
            } catch (\PhpParser\Error $e) {
                $context->sytaxError($e, $filepath);
            }
        }

        $output->writeln('');
    }
}
