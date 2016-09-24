<?php

namespace Tests\PHPSA\Compiler\Expression\AssignOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class BitwiseOrTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return array(
            array(0, 5, 5),
            array(1, 5, 5),
            array(4, 5, 5),
            array(-1, 5, -1),
            array(1.4, 5, 5),
            array(-19.7, 1, -19),
            array(true, true, 1),
            array(false, true, 1),
            array(true, false, 1),
            array(false, false, 0),
        );
    }

    /**
     * Tests {var} |= {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testSimpleSuccessCompile($a, $b, $c)
    {
        $baseExpression = new Node\Expr\AssignOp\BitwiseOr(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    /**
     * tests variable type unknown
     */
    public function testUnexpectedTypeFirstArg()
    {
        $baseExpression = new Node\Expr\AssignOp\BitwiseOr(
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
        $baseExpression = new Node\Expr\AssignOp\BitwiseOr(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
