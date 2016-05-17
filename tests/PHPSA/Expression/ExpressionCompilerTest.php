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
class ExpressionCompilerTest extends \Tests\PHPSA\TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Passed $expression must be instance of PhpParser\Node\Expr\UnaryMinus
     */
    public function testPassUnexpectedExpression()
    {
        $expr = new UnaryMinus();
        self::assertSame('PhpParser\Node\Expr\UnaryMinus', $expr->getName());

        $expr->pass($this->newFakeScalarExpr(), $this->getContext());
    }
}
