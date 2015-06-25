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

    /**
     * Data provider for Plus {int} + {int} = {int}
     *
     * @return array
     */
    public function testIntToIntDataProvider()
    {
        return array(
            array(-1, -1, -2),
            array(-1, 0, -1),
            array(0, -1, -1),
            array(-1, 2, 1),
            array(2, -1, 1),
            array(0, 0, 0),
            array(0, 1, 1),
            array(1, 0, 1),
            array(1, 2, 3),
            array(2, 1, 3),
            array(25, 25, 50),
            array(50, 50, 100),
        );
    }

    /**
     * @dataProvider testIntToIntDataProvider
     */
    public function testPlusIntToInt($a, $b, $c)
    {
        $baseExpression = new Node\Expr\BinaryOp\Plus(
            new Node\Scalar\LNumber($a),
            new Node\Scalar\LNumber($b)
        );

        $visitor = new Expression($baseExpression, $this->getContext());
        $compiledExpression = $visitor->compile($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::LNUMBER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
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