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
        
        $this->assertTrue($application->has("check"));
        $this->assertTrue($application->has("compile"));
        $this->assertTrue($application->has("config:dump-reference"));
        $this->assertTrue($application->has("config:dump-documentation"));
    }

   /**
     * @covers \PHPSA\Application::getConfiguration
     */
    public function testGetConfiguration()
    {
        $application = new Application();

        $this->assertInstanceOf('\PHPSA\Configuration', $application->getConfiguration());
    }

    /**
     * @covers \PHPSA\Application::getIssuesCollector
     */
    public function testGetIssueCollector()
    {
        $application = new Application();

        $this->assertInstanceOf('\PHPSA\IssuesCollector', $application->getIssuesCollector());
    }
}
