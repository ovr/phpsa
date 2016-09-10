<?php

namespace PHPSA\Compiler\Expression\Casts;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class UnsetCast extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Cast\Unset_';

    /**
     * (unset) {$expr}
     *
     * @param \PhpParser\Node\Expr\Cast\Unset_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiledExpression = $context->getExpressionCompiler()->compile($expr->expr);

        return new CompiledExpression(CompiledExpression::NULL, null);
    }
}
