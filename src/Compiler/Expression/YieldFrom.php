<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class YieldFrom extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\YieldFrom';

    /**
     * yield from {expr}
     *
     * @param \PhpParser\Node\Expr\YieldFrom $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiled = $context->getExpressionCompiler()->compile($expr->expr);

        // @TODO implement yield from
        return new CompiledExpression();
    }
}
