<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class IncludeOp extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Include_';

    /**
     * include {expr}, require {expr}
     *
     * @param \PhpParser\Node\Expr\Include_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiled = $context->getExpressionCompiler()->compile($expr->expr);

        if ($compiled->isString()) {
            return CompiledExpression::fromZvalValue(1);
        }

        return CompiledExpression::fromZvalValue(false);
    }
}
