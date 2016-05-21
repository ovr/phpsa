<?php

namespace Tests\PHPSA;

use PhpParser\ParserFactory;
use PHPSA\Analyzer\EventListener\ExpressionListener;
use PHPSA\Analyzer\EventListener\StatementListener;
use PHPSA\Application;
use PHPSA\Context;
use PHPSA\Definition\FileParser;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Webiny\Component\EventManager\EventManager;
use PHPSA\Compiler;
use PhpParser\Node;
use PHPSA\Analyzer\Pass as AnalyzerPass;

class AnalyzeFixturesTest extends TestCase
{
    public function provideTestParseAndDump()
    {
        $iter = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                __DIR__ . '/../analyze-fixtures'
            ),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        /** @var \SplFileInfo $file */
        foreach ($iter as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $contents = file_get_contents($file);
            list (, $expected) = explode('----------------------------', $contents);

            yield [$file->getPathname(), $expected];
        }
    }

    /**
     * @dataProvider provideTestParseAndDump
     *
     * @param $file
     * @param $expectedDump
     * @throws \PHPSA\Exception\RuntimeException
     * @throws \Webiny\Component\EventManager\EventManagerException
     */
    public function testParseAndDump($file, $expectedDump)
    {
        $compiler = new Compiler();

        $fileParser = new FileParser(
            (new ParserFactory())->create(
                ParserFactory::PREFER_PHP7,
                new \PhpParser\Lexer\Emulative(
                array(
                        'usedAttributes' => array(
                            'comments',
                            'startLine',
                            'endLine',
                            'startTokenPos',
                            'endTokenPos'
                        )
                    )
                )
            ),
            $compiler
        );

        $em = EventManager::getInstance();

        $bufferOutput = new \Symfony\Component\Console\Output\BufferedOutput();
        $context = new Context(
            $bufferOutput,
            $application = new Application(),
            $em
        );
        $application->compiler = $compiler;

        $fileParser->parserFile($file, $context);

        $em->listen(Compiler\Event\ExpressionBeforeCompile::EVENT_NAME)
            ->handler(
                new ExpressionListener(
                    [
                        Node\Expr\FuncCall::class => [
                            new AnalyzerPass\Expression\FunctionCall\AliasCheck(),
                            new AnalyzerPass\Expression\FunctionCall\DebugCode(),
                            new AnalyzerPass\Expression\FunctionCall\RandomApiMigration(),
                            new AnalyzerPass\Expression\FunctionCall\UseCast(),
                            new AnalyzerPass\Expression\FunctionCall\DeprecatedIniOptions(),
                        ],
                        Node\Expr\Array_::class => [
                            new AnalyzerPass\Expression\ArrayShortDefinition()
                        ]
                    ]
                )
            )
            ->method('beforeCompile');

        $em->listen(Compiler\Event\StatementBeforeCompile::EVENT_NAME)
            ->handler(
                new StatementListener(
                    [
                        Node\Stmt\ClassMethod::class => [
                            new AnalyzerPass\Statement\MethodCannotReturn()
                        ]
                    ]
                )
            )
            ->method('beforeCompile');

        $compiler->compile($context);

        self::assertSame(
            json_encode($application->getIssuesCollector()->getIssues()),
            trim($expectedDump)
        );
    }
}
