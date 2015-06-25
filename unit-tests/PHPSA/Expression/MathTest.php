<?php

namespace Tests\PHPSA\Expression;

use PhpParser\Node;
use PHPSA\Application;
use PHPSA\CompiledExpression;
use PHPSA\Definition\ClassDefinition;
use PHPSA\Visitor\Expression;
use Symfony\Component\Console\Output\ConsoleOutput;

class MathTest extends \PHPUnit_Framework_TestCase
{
    public function testPlusIntToInt()
    {
        $baseExpression = new Node\Expr\BinaryOp\Plus(
            new Node\Scalar\LNumber(1),
            new Node\Scalar\LNumber(1)
        );

        $context = new \PHPSA\Context(new ConsoleOutput(), new Application());
        $context->setScope(new ClassDefinition('MathTest'));

        $visitor = new Expression($baseExpression, $context);
        $compiledExpression = $visitor->compile($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
//        $this->assertSame(CompiledExpression::LNUMBER, $compiledExpression->getType());
        $this->assertSame(2, $compiledExpression->getValue());
    }
}