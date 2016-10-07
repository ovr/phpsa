<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class YieldOp extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Yield_';

    /**
     * yield {value}, yield {key} => {value}
     *
     * @param \PhpParser\Node\Expr\Yield_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $key = $context->getExpressionCompiler()->compile($expr->key);
        $value = $context->getExpressionCompiler()->compile($expr->value);

        // @TODO implement yield
        return new CompiledExpression();
    }
}
