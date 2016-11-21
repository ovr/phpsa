<?php

namespace Tests\PHPSA;

use PHPSA\Application;

class ApplicationTest extends TestCase
{
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
