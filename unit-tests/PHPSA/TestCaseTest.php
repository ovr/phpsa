<?php

namespace Tests\PHPSA;

class TestCaseTest extends TestCase
{
    public function testGetContext()
    {
        $result = $this->getContext();
        $this->assertInstanceOf('PHPSA\Context', $result);
    }
}
