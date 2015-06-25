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
    /**
     * @return \PHPSA\Context
     */
    protected function getContext()
    {
        $context = new \PHPSA\Context(new ConsoleOutput(), new Application());
        $context->setScope(new ClassDefinition('MathTest'));

        return $context;
    }

    public function testPlusIntToInt()
    {
        $baseExpression = new Node\Expr\BinaryOp\Plus(
            new Node\Scalar\LNumber(1),
            new Node\Scalar\LNumber(1)
        );

        $visitor = new Expression($baseExpression, $this->getContext());
        $compiledExpression = $visitor->compile($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::LNUMBER, $compiledExpression->getType());
        $this->assertSame(2, $compiledExpression->getValue());
    }

    public function testPlusIntToFloat()
    {
        $baseExpression = new Node\Expr\BinaryOp\Plus(
            new Node\Scalar\LNumber(1),
            new Node\Scalar\DNumber(1.5)
        );

        $visitor = new Expression($baseExpression, $this->getContext());
        $compiledExpression = $visitor->compile($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::DNUMBER, $compiledExpression->getType());
        $this->assertSame(2.5, $compiledExpression->getValue());
    }

    public function testPlusFloatToInt()
    {
        $baseExpression = new Node\Expr\BinaryOp\Plus(
            new Node\Scalar\DNumber(1.5),
            new Node\Scalar\LNumber(1)
        );

        $visitor = new Expression($baseExpression, $this->getContext());
        $compiledExpression = $visitor->compile($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::DNUMBER, $compiledExpression->getType());
        $this->assertSame(2.5, $compiledExpression->getValue());
    }

    public function testPlusFloatToFloat()
    {
        $baseExpression = new Node\Expr\BinaryOp\Plus(
            new Node\Scalar\DNumber(1.5),
            new Node\Scalar\LNumber(1.5)
        );

        $visitor = new Expression($baseExpression, $this->getContext());
        $compiledExpression = $visitor->compile($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::DNUMBER, $compiledExpression->getType());
        $this->assertSame(3.0, $compiledExpression->getValue());
    }
}