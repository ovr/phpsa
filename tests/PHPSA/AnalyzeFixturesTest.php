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
    /**
     * @var EventManager
     */
    static protected $em;

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

        $context = new Context(
            new \Symfony\Component\Console\Output\NullOutput(),
            $application = new Application(),
            $this->getEventManager()
        );
        $application->compiler = $compiler;

        $fileParser->parserFile($file, $context);

        $compiler->compile($context);

        self::assertEquals(
            $application->getIssuesCollector()->getIssues(),
            json_decode(trim($expectedDump), true)
        );
    }

    /**
     * @return EventManager
     * @throws \Webiny\Component\EventManager\EventManagerException
     */
    protected function getEventManager()
    {
        if (self::$em) {
            return self::$em;
        }

        self::$em = EventManager::getInstance();
        \PHPSA\Analyzer\Factory::factory(self::$em);

        return self::$em;
    }
}
