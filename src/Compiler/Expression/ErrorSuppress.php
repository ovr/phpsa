<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class ErrorSuppress extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\ErrorSuppress';

    /**
     * @{expr}
     *
     * @param \PhpParser\Node\Expr\ErrorSuppress $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiled = $context->getExpressionCompiler()->compile($expr->expr);

        return CompiledExpression::fromZvalValue($compiled->getValue());
    }
}
