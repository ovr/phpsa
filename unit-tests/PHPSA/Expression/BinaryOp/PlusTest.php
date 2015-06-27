<?php

namespace Tests\PHPSA\Expression\BinaryOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Visitor\Expression;

class PlusTest extends \Tests\PHPSA\TestCase
{
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
     * Data provider for Plus {int} + {float} = {int}
     *
     * @return array
     */
    public function testIntToFloatDataProvider()
    {
        return array(
            array(1, -1.5, -0.5),
            array(1, -1.0, 0.0),
            array(-1, -1.0, -2.0),
            array(-1, -2.55, -3.55),
            array(1, 1.5, 2.5),
            array(1, 2.5, 3.5),
            array(1, 4.5, 5.5),
            array(1, 4.75, 5.75),
            array(25.25, 24.75, 50.0)
        );
    }

    /**
     * Tests {int} + {int} = {int}
     *
     * @dataProvider testIntToIntDataProvider
     */
    public function testPlusIntToInt($a, $b, $c)
    {
        $baseExpression = new Node\Expr\BinaryOp\Plus(
            new Node\Scalar\LNumber($a),
            new Node\Scalar\LNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::LNUMBER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Tests {int} + {float} = {float}
     *
     * @dataProvider testIntToFloatDataProvider
     */
    public function testPlusIntToFloat($a, $b, $c)
    {
        $baseExpression = new Node\Expr\BinaryOp\Plus(
            new Node\Scalar\LNumber($a),
            new Node\Scalar\DNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::DNUMBER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Tests {float} + {int} = {float}
     *
     * testPlusFloatToInt($b, $a - it's special to use already defined fixtures
     *
     * @dataProvider testIntToFloatDataProvider
     */
    public function testPlusFloatToInt($b, $a, $c)
    {
        $baseExpression = new Node\Expr\BinaryOp\Plus(
            new Node\Scalar\DNumber($a),
            new Node\Scalar\LNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::DNUMBER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Tests {float} + {float} = {float}
     *
     * @dataProvider testIntToFloatDataProvider
     */
    public function testPlusFloatToFloat($a, $b, $c)
    {
        $baseExpression = new Node\Expr\BinaryOp\Plus(
            /**
             * float casting to use already defined fixtures (float) {int} + {float} = {float}
             */
            new Node\Scalar\DNumber((float) $a),
            new Node\Scalar\DNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::DNUMBER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }
}
