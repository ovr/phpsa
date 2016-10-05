<?php

namespace Tests\PHPSA;

use PHPSA\IssuesCollector;

class IssuesCollectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \PHPSA\IssuesCollector::addIssue()
     * @covers \PHPSA\IssuesCollector::getIssues()
     */
    public function testAddingIssue()
    {
        $collector = new IssuesCollector();

        $this->assertNull($collector->addIssue(100, 'Test issue', __FILE__, 26));
        $this->assertCount(1, $collector->getIssues());
        $this->assertSame(
            [['type' => 100, 'message' => 'Test issue', 'file' => __FILE__, 'line' => 26]],
            $collector->getIssues()
        );
    }
}
