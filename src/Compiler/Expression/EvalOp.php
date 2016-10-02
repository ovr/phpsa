<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class EvalOp extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Eval_';

    /**
     * eval({expr})
     *
     * @param \PhpParser\Node\Expr\Eval_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $context->getExpressionCompiler()->compile($expr->expr);

        return CompiledExpression::fromZvalValue(null);
    }
}
