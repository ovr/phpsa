<?php

namespace Tests\PHPSA\Defintion;

use Tests\PHPSA\TestCase;

class ClassDefintionTest extends TestCase
{
    public function testSimpleInstance()
    {
        $classDefinition = new \PHPSA\Definition\ClassDefinition('MyTestClass');
        $this->assertSame('MyTestClass', $classDefinition->getName());
    }
}
