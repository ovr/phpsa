<?php

namespace Tests\PHPSA;

use Symfony\Component\Console\Output\ConsoleOutput;
use PHPSA\Definition\ClassDefinition;
use PHPSA\Application;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPSA\Context
     */
    protected function getContext()
    {
        $context = new \PHPSA\Context(new ConsoleOutput(), new Application());
        $context->setScope(new ClassDefinition('MathTest'));

        return $context;
    }
}