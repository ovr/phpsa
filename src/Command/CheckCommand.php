<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Command;

use PHPSA\Definition\ClassDefinition;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MyNodeVisitor extends \PhpParser\NodeVisitorAbstract
{
    private $tokens;

    public function setTokens(array $tokens)
    {
        $this->tokens = $tokens;
    }

    public function leaveNode(\PhpParser\Node $node)
    {
        if ($node instanceof PhpParser\Node\Stmt\Property) {
            var_dump(isDeclaredUsingVar($this->tokens, $node));
        }
    }
}

class CheckCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('check')
            ->setDescription('SPA')
            ->setDefinition(array(
                new InputArgument('path', InputArgument::REQUIRED),
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
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

        try {
            $code = file_get_contents(__DIR__ . '/../../tests/simple/test-1/1.php');
            $stmts = $parser->parse($code);


            /**
             * Step 1 Precompile
             */

            /**
             * @var ClassDefinition[]
             */
            $classes = [];

            foreach ($stmts as $st) {
                if ($st instanceof \PhpParser\Node\Stmt\Class_) {
                    $classDefintion = new \PHPSA\Definition\ClassDefinition($st->name);

                    /** @var \PhpParser\Node\Stmt\ClassMethod $method */
                    foreach ($st->stmts as $method) {
                        $method = new \PHPSA\Definition\ClassMethod($method->name, $method->stmts);

                        $classDefintion->addMethod($method);
                    }

                    $classes[] = $classDefintion;
                }
            }

            $context = new \PHPSA\Context();
            $context->application = $this->getApplication();
            $context->output = $output;

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
            echo 'Parse Error: ', $e->getMessage();
        }
    }
}
