<?php

namespace Tests\PHPSA\Expression\BinaryOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Visitor\Expression;

class DivTest extends \Tests\PHPSA\TestCase
{
    /**
     * Data provider for Div {int} / {int} = {int}
     *
     * @return array
     */
    public function testDivIntResultDataProvider()
    {
        return array(
            array(-1, -1, 1),
            array(-1, 1, -1),
            array(1, 1, 1),
            array(0, 1, 0),
            array(25, 25, 1),
            array(50, 50, 1),
            array(500, 50, 10),
            array(5000, 50, 100)
        );
    }

    /**
     * Tests {int} / {int} = {int}
     *
     * @dataProvider testDivIntResultDataProvider
     */
    public function testDivIntToIntWithIntResult($a, $b, $c)
    {
        $this->assertInternalType('int', $a);
        $this->assertInternalType('int', $b);
        $this->assertInternalType('int', $c);

        $baseExpression = new Node\Expr\BinaryOp\Div(
            new Node\Scalar\LNumber($a),
            new Node\Scalar\LNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::LNUMBER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Data provider for Div {int} / {double} = {double}
     *
     * @return array
     */
    public function testDivFloatResultDataProvider()
    {
        return array(
            array(-1, -1.25, 0.8),
            array(-1, 1.25, -0.8),
            array(1, 1.25, 0.8),
            array(0, 1.25, 0.0),
            array(25, 12.5, 2.0),
            array(25, 6.25, 4.0),
            array(25, 3.125, 8.0),
        );
    }

    /**
     * Tests {int} / {double} = {double}
     *
     * @dataProvider testDivFloatResultDataProvider
     */
    public function testDivIntToDoubleWithDoubleResult($a, $b, $c)
    {
        $this->assertInternalType('int', $a);
        $this->assertInternalType('double', $b);
        $this->assertInternalType('double', $c);

        $baseExpression = new Node\Expr\BinaryOp\Div(
            new Node\Scalar\LNumber($a),
            new Node\Scalar\DNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::DNUMBER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Data provider for Div {double} / {double} = {double}
     *
     * @return array
     */
    public function testDivDoubleToDoubleResultDataProvider()
    {
        return array(
            array(-1.25, -1, 1.25),
            array(1.25, -1, -1.25),
            array(1.25, 1, 1.25),
            array(12.5, 25, 1/2),
            array(6.25, 25, 1/4),
            array(3.125, 25, 1/8),
        );
    }

    /**
     * Tests {double} / {int} = {double}
     *
     * @dataProvider testDivDoubleToDoubleResultDataProvider
     */
    public function testDivDoubleToIntWithDoubleResult($a, $b, $c)
    {
        $this->assertInternalType('double', $a);
        $this->assertInternalType('int', $b);
        $this->assertInternalType('double', $c);

        $baseExpression = new Node\Expr\BinaryOp\Div(
            new Node\Scalar\DNumber($a),
            new Node\Scalar\LNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::DNUMBER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * Tests {double} / {double} = {double}
     *
     * @dataProvider testDivDoubleToDoubleResultDataProvider
     */
    public function testDivDoubleToDoubleWithDoubleResult($a, $b, $c)
    {
        $b = (float) $b;

        $this->assertInternalType('double', $a);
        $this->assertInternalType('double', $b);
        $this->assertInternalType('double', $c);

        $baseExpression = new Node\Expr\BinaryOp\Div(
            new Node\Scalar\DNumber($a),
            new Node\Scalar\DNumber($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOf('PHPSA\CompiledExpression', $compiledExpression);
        $this->assertSame(CompiledExpression::DNUMBER, $compiledExpression->getType());
        $this->assertSame((float) $c, $compiledExpression->getValue());
    }
}
