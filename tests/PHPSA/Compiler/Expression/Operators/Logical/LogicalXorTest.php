<?php

namespace Tests\PHPSA\Compiler\Expression\Operators\Logical;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;

class LogicalXorTest extends \Tests\PHPSA\TestCase
{
    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            [true, true, false],
            [true, false, true],
            [false, true, true],
            [false, false, false],
            [null, false, false],
            [false, null, false],
            [null, null, false],
            [true, null, true],
            [null, true, true],
            [1, true, false],
            [1.4, false, true],
            [1, false, true],
            [-1, false, true],
            ["a", false, true],
            [[], [], false],
            [[], "a", true],
        ];
    }

    /**
     * Tests {expr} xor {expr} = {expr}
     *
     * @dataProvider getDataProvider
     */
    public function testSimpleSuccessCompile($a, $b, $c)
    {
        $baseExpression = new Node\Expr\BinaryOp\LogicalXor(
            $this->newScalarExpr($a),
            $this->newScalarExpr($b)
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame($c, $compiledExpression->getValue());
    }

    public function testUnexpectedTypes()
    {
        $baseExpression = new Node\Expr\BinaryOp\LogicalXor(
            $this->newScalarExpr(1),
            $this->newFakeScalarExpr()
        );
        $compiledExpression = $this->compileExpression($baseExpression);

        $this->assertInstanceOfCompiledExpression($compiledExpression);
        $this->assertSame(CompiledExpression::UNKNOWN, $compiledExpression->getType());
        $this->assertSame(null, $compiledExpression->getValue());
    }
}
