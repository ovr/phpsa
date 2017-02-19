<?php

namespace Tests\PHPSA\Compiler\Expression;

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
        self::assertInstanceOfCompiledExpression($compiledExpression);

        self::assertSame(CompiledExpression::INTEGER, $compiledExpression->getType());
        self::assertSame($scalar->value, $compiledExpression->getValue());
    }

    public function testPassDouble()
    {
        $scalar = $this->newScalarExpr(1.0);
        $compiledExpression = $this->compileExpression($scalar, $this->getContext());
        self::assertInstanceOfCompiledExpression($compiledExpression);

        self::assertSame(CompiledExpression::DOUBLE, $compiledExpression->getType());
        self::assertSame($scalar->value, $compiledExpression->getValue());
    }

    public function testPassBooleanTrue()
    {
        $scalar = $this->newScalarExpr(true);
        $compiledExpression = $this->compileExpression($scalar, $this->getContext());
        self::assertInstanceOfCompiledExpression($compiledExpression);

        self::assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        self::assertSame($scalar->value, $compiledExpression->getValue());
    }

    public function testPassBooleanFalse()
    {
        $scalar = $this->newScalarExpr(false);
        $compiledExpression = $this->compileExpression($scalar, $this->getContext());
        self::assertInstanceOfCompiledExpression($compiledExpression);

        self::assertSame(CompiledExpression::BOOLEAN, $compiledExpression->getType());
        self::assertSame($scalar->value, $compiledExpression->getValue());
    }

    public function testPassString()
    {
        $scalar = $this->newScalarExpr("test string");
        $compiledExpression = $this->compileExpression($scalar, $this->getContext());
        self::assertInstanceOfCompiledExpression($compiledExpression);

        self::assertSame(CompiledExpression::STRING, $compiledExpression->getType());
        self::assertSame($scalar->value, $compiledExpression->getValue());
    }

    public function testPassNull()
    {
        $scalar = $this->newScalarExpr(null);
        $compiledExpression = $this->compileExpression($scalar, $this->getContext());
        self::assertInstanceOfCompiledExpression($compiledExpression);

        self::assertSame(CompiledExpression::NULL, $compiledExpression->getType());
        self::assertSame($scalar->value, $compiledExpression->getValue());
    }
}
