<?php

namespace Tests\PHPSA;

use PhpParser\ParserFactory;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class CompilingFixturesTest extends TestCase
{
    public function provideTestParseAndDump()
    {
        $iter = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                __DIR__ . '/../compiling-fixtures'
            ),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iter as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $contents = file_get_contents($file);
            yield $file->getBasename() => explode('----------------------------', $contents);
        }
    }

    /** @dataProvider provideTestParseAndDump */
    public function testParseAndDump($code, $expectedDump)
    {
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
        $ast = $parser->parse($code);

        $expressionCompiler = $this->getContext()->getExpressionCompiler();

        foreach ($ast as $node) {
            $compiledExpression = $expressionCompiler ->compile($node);
            self::assertInstanceOfCompiledExpression($compiledExpression);
        }

        self::assertSame(
            json_encode($compiledExpression->__debugInfo()),
            trim($expectedDump)
        );
    }
}
