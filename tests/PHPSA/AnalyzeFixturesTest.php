<?php

namespace Tests\PHPSA;

use PhpParser\ParserFactory;
use PHPSA\Analyzer;
use PHPSA\Application;
use PHPSA\Configuration;
use PHPSA\Context;
use PHPSA\Definition\FileParser;
use PHPSA\Issue;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Webiny\Component\EventManager\EventManager;
use PHPSA\Compiler;

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
            list (, $analyzer, $expected) = explode('----------------------------', $contents);

            yield [$file->getPathname(), trim($analyzer), trim($expected)];
        }
    }

    /**
     * @dataProvider provideTestParseAndDump
     *
     * @param $file
     * @param string $analyzer
     * @param string $expectedDump
     * @throws \PHPSA\Exception\RuntimeException
     * @throws \Webiny\Component\EventManager\EventManagerException
     */
    public function testParseAndDump($file, $analyzer, $expectedDump)
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
            $this->getEventManager($analyzer)
        );
        $application->compiler = $compiler;

        $fileParser->parserFile($file, $context);

        $compiler->compile($context);

        $expectedArray = json_decode($expectedDump, true);
        $expectedType = $expectedArray[0]["type"];
        $issues = array_map(
            // @todo Remove after moving all notices on Issue(s)
            function (Issue $issue) {
                $location = $issue->getLocation();

                return [
                    'type' => $issue->getCheckName(),
                    'message' => $issue->getDescription(),
                    'file' => $location->getFileName(),
                    'line' => $location->getLineStart(),
                ];
            },
            $application->getIssuesCollector()->getIssues()
        );
        
        foreach ($expectedArray as $check) {
            self::assertContains($check, $issues, $file); // every expected Issue is in the collector
        }

        foreach ($issues as $check) {
            if ($check["type"] == $expectedType) {
                self::assertContains($check, $expectedArray, $file); // there is no other issue in the collector with the same type
            }
        }
    }

    /**
     * @param string $analyzerName
     * @return EventManager
     * @throws \Webiny\Component\EventManager\EventManagerException
     */
    protected function getEventManager($analyzerName)
    {
        if (!class_exists($analyzerName, true)) {
            throw new \InvalidArgumentException("Analyzer with name: {$analyzerName} doesnot exist");
        }

        /** @var \PHPSA\Analyzer\Pass\Metadata $metaData */
        $metaData = $analyzerName::getMetadata();
        if (!$metaData->allowsPhpVersion(PHP_VERSION)) {
            parent::markTestSkipped(
                sprintf(
                    'We cannot tests %s with %s because PHP required version is %s',
                    $analyzerName,
                    PHP_VERSION,
                    $metaData->getRequiredPhpVersion()
                )
            );
        }
        
        $analyzerConfiguration = $metaData->getConfiguration();
        $analyzerConfiguration->attribute('enabled', true);

        $config = [
            $analyzerName::getMetadata()->getConfiguration()
        ];

        $em = EventManager::getInstance();
        $configuration = new Configuration([], $config);
        \PHPSA\Analyzer\Factory::factory($em, $configuration);

        return $em;
    }
}
