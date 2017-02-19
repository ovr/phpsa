<?php

namespace Tests\PHPSA;

use PHPSA\Application;

class ApplicationTest extends TestCase
{
    /**
     * @covers \PHPSA\Application::__construct
     */
    public function testConstructor()
    {
        $application = new Application();
        
        self::assertTrue($application->has("check"));
        self::assertTrue($application->has("compile"));
        self::assertTrue($application->has("config:dump-reference"));
        self::assertTrue($application->has("config:dump-documentation"));
    }

   /**
     * @covers \PHPSA\Application::getConfiguration
     */
    public function testGetConfiguration()
    {
        $application = new Application();

        self::assertInstanceOf('\PHPSA\Configuration', $application->getConfiguration());
    }

    /**
     * @covers \PHPSA\Application::getIssuesCollector
     */
    public function testGetIssueCollector()
    {
        $application = new Application();

        self::assertInstanceOf('\PHPSA\IssuesCollector', $application->getIssuesCollector());
    }
}
