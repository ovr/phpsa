<?php

namespace PHPSA\Compiler\Expression\Casts;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class ArrayCast extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Cast\Array_';

    /**
     * (array) {$expr}
     *
     * @param \PhpParser\Node\Expr\Cast\Array_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiledExpression = $context->getExpressionCompiler()->compile($expr->expr);

        if ($compiledExpression->isTypeKnown()) {
            return new CompiledExpression(CompiledExpression::ARR, (array) $compiledExpression->getValue());
        }

        return new CompiledExpression();
    }
}
