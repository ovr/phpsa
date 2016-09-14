<?php

namespace Tests\PHPSA\Expression\Operators\Bitwise;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class BitwiseXorTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return array(
            array(0, 5, 5),
            array(1, 5, 4),
            array(4, 5, 1),
            array(-1, 5, -6),
            array(1.4, 5, 4),
            array(-19.7, 1, -20),
            array(true, true, 0),
            array(false, true, 1),
            array(true, false, 1),
            array(false, false, 0),
        );
    }

    /**
     * Tests {expr} ^ {expr} = {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testSimpleSuccessCompile($a, $b, $c)
    {
        $baseExpression = new Node\Expr\BinaryOp\BitwiseXor(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    public function testUnexpectedTypeFirstArg()
    {
        $baseExpression = new Node\Expr\BinaryOp\BitwiseXor(
            $this->newFakeScalarExpr(),
            $this->newScalarExpr(1)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }

    public function testUnexpectedTypeSecondArg()
    {
        $baseExpression = new Node\Expr\BinaryOp\BitwiseXor(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
