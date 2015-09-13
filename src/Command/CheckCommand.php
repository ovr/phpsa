<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Command;

use PHPSA\AliasManager;
use PHPSA\Application;
use PHPSA\Compiler;
use PHPSA\Context;
use PHPSA\Definition\ClassDefinition;
use PHPSA\Definition\ClassMethod;
use PHPSA\Definition\FunctionDefinition;
use PHPSA\ParseTask;
use Pool;
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
            ->addArgument('path', InputArgument::OPTIONAL, 'Path to check file or directory', '.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');

        if (extension_loaded('xdebug')) {
            $output->writeln('<error>It is highly recommended to disable the XDebug extension before invoking this command.</error>');
        }

        $parser = new Parser(new \PhpParser\Lexer\Emulative(
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

        $context = new Context($output, $application);

        /**
         * Store option's in application's configuration
         */
        $application->getConfiguration()->setValue('blame', $input->getOption('blame'));

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

            $output->writeln(sprintf('found <info>%d files</info>', $count));

            if ($count > 100) {
                $output->writeln('<comment>Caution: You are trying to scan a lot of files; this might be slow. For bigger libraries, consider setting up a dedicated platform or using ci.lowl.io.</comment>');
            }

            $output->writeln('');

            $compiler = $this->getCompiler();
            $pool = new Pool(1, 'PHPSA\ParserWorker', [$parser, $compiler]);
            $aliasManager = new AliasManager();


            class_exists("PhpParser\Node\Name", true);
            class_exists("PhpParser\Node\Stmt\Namespace_", true);
            class_exists("PhpParser\Comment\Doc", true);
            class_exists("PhpParser\Node\Stmt\Class_", true);
            class_exists("PhpParser\Node\Expr\ConstFetch", true);
            class_exists("PhpParser\Node\Stmt\Do_", true);
            class_exists("PhpParser\Node\Stmt\ClassMethod", true);
            class_exists("PhpParser\Node\Stmt\Return_", true);
            class_exists("PhpParser\Node\Param", true);
            class_exists("PhpParser\Node\Expr\Variable", true);
            class_exists("PhpParser\Node\Stmt\UseUse", true);
            class_exists("PhpParser\Node\Stmt\Use_", true);
            class_exists("PhpParser\Node\Expr\New_", true);
            class_exists("PhpParser\Node\Scalar\LNumber", true);
            class_exists("PhpParser\Node\Expr\PostDec", true);
            class_exists("PhpParser\Node\Expr\BinaryOp\Smaller", true);
            class_exists("PhpParser\Node\Expr\PostInc", true);
            class_exists("PhpParser\Node\Scalar\DNumber", true);
            class_exists("PhpParser\Node\Scalar\String_", true);
            class_exists("PhpParser\Node\Expr\Array_", true);
            class_exists("PhpParser\Node\Expr\ArrayItem", true);
            class_exists("PhpParser\Node\Stmt\If_", true);
            class_exists("PhpParser\Node\Expr\BooleanNot", true);
            class_exists("PhpParser\Node\Expr\BinaryOp\Identical", true);
            class_exists("PhpParser\Node\Expr\BinaryOp\Equal", true);
            class_exists("PhpParser\Node\Stmt\ElseIf_", true);
            class_exists("PhpParser\Node\Stmt\Else_", true);
            class_exists("PhpParser\Node\Expr\BinaryOp\BooleanOr", true);
            class_exists("PhpParser\Node\Expr\StaticCall", true);
            class_exists("PhpParser\Node\Expr\BinaryOp\Plus", true);
            class_exists("PhpParser\Node\Expr\BinaryOp\Minus", true);
            class_exists("PhpParser\Node\Expr\BinaryOp\Div", true);
            class_exists("PhpParser\Node\Expr\BinaryOp\Mul", true);
            class_exists("PhpParser\Node\Expr\BinaryOp\BitwiseXor", true);
            class_exists("PhpParser\Node\Expr\Assign", true);
            class_exists("PhpParser\Node\Stmt\Function_", true);
            class_exists("PhpParser\Node\Expr\FuncCall", true);
            class_exists("PhpParser\Node\Arg", true);
            class_exists("PhpParser\Node\Expr\MethodCall", true);
            class_exists("PhpParser\Node\Const_", true);
            class_exists("PhpParser\Node\Stmt\ClassConst", true);
            class_exists("PhpParser\Node\Expr\ClassConstFetch", true);
            class_exists("PhpParser\Node\Stmt\Const_", true);
            class_exists("PhpParser\Node\Stmt\PropertyProperty", true);
            class_exists("PhpParser\Node\Stmt\Property", true);
            class_exists("PhpParser\Node\Expr\PropertyFetch", true);
            class_exists("PhpParser\Error", true);
            class_exists("PhpParser\Node\Expr\Cast\Bool_", true);
            class_exists("PhpParser\Node\Expr\Cast\String_", true);
            class_exists("PhpParser\Node\Expr\Cast\Int_", true);
            class_exists("PhpParser\Node\Stmt\For_", true);
            class_exists("PhpParser\Node\Stmt\Case_", true);
            class_exists("PhpParser\Node\Stmt\While_", true);
            class_exists("PhpParser\Node\Stmt\Break_", true);
            class_exists("PhpParser\Node\Expr\BinaryOp\Greater", true);
            class_exists("PhpParser\Node\Stmt\Switch_", true);

            class_exists("PHPSA\Definition\ClassDefinition", true);
            class_exists("PHPSA\Definition\FunctionDefinition", true);
            class_exists("PHPSA\Definition\ClassMethod", true);

            /** @var SplFileInfo $file */
            foreach ($directoryIterator as $file) {
                if ($file->getExtension() != 'php') {
                    continue;
                }

                $pool->submit(new ParseTask($file->getPathname(), clone $context));
                break;
            }

            while ($pool->collect(function($work){
                return $work->isGarbage();
            })) continue;
//            var_dump($pool);
            $pool->shutdown();

            var_dump(spl_object_hash($compiler));
            var_dump($compiler);
            var_dump($pool);
        } elseif (is_file($path)) {
//            $this->parserFile($path, $parser, $context);
        }


//        /**
//         * Step 2 Recursive check ...
//         */
//        $application->compiler->compile($context);

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
