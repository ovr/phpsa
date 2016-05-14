<?php

namespace Tests\PHPSA\Expression\BinaryOp;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\Operators\UnaryMinus;

/**
 * Class ExpressionCompilerTest
 * @package Tests\PHPSA\Expression\BinaryOp
 */
class ScalarCompilerTest extends \Tests\PHPSA\TestCase
{
    public function testPassInteger()
    {
        $scalar = $this->newScalarExpr(1);
        $compiledExpression = $this->compileExpression($scalar, $this->getContext());
        $this->assertInstanceOfCompiledExpression($compiledExpression);

        $this->assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        $this->assertSame($scalar->value, $compiledExpression->getValue());
    }

    public function testPassDouble()
    {
        $scalar = $this->newScalarExpr(1.0);
        $compiledExpression = $this->compileExpression($scalar, $this->getContext());
        $this->assertInstanceOfCompiledExpression($compiledExpression);

        $this->assertSame(CompiledExpression::DOUBLE, $compiledExpression->getType());
        $this->assertSame($scalar->value, $compiledExpression->getValue());
    }

    public function testPassBooleanTrue()
    {
        $scalar = $this->newScalarExpr(true);
        $compiledExpression = $this->compileExpression($scalar, $this->getContext());
        $this->assertInstanceOfCompiledExpression($compiledExpression);

        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame($scalar->value, $compiledExpression->getValue());
    }

    public function testPassBooleanFalse()
    {
        $scalar = $this->newScalarExpr(false);
        $compiledExpression = $this->compileExpression($scalar, $this->getContext());
        $this->assertInstanceOfCompiledExpression($compiledExpression);

        $this->assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        $this->assertSame($scalar->value, $compiledExpression->getValue());
    }

    public function testPassString()
    {
        $scalar = $this->newScalarExpr("test string");
        $compiledExpression = $this->compileExpression($scalar, $this->getContext());
        $this->assertInstanceOfCompiledExpression($compiledExpression);

        $this->assertSame(CompiledExpression::STRING, $compiledExpression->getType());
        $this->assertSame($scalar->value, $compiledExpression->getValue());
    }

    public function testPassNull()
    {
        $scalar = $this->newScalarExpr(null);
        $compiledExpression = $this->compileExpression($scalar, $this->getContext());
        $this->assertInstanceOfCompiledExpression($compiledExpression);

        $this->assertSame(CompiledExpression::NULL, $compiledExpression->getType());
        $this->assertSame($scalar->value, $compiledExpression->getValue());
    }
}
