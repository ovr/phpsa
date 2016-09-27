<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class CloneOp extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Clone_';

    /**
     * clone {expr}
     *
     * @param \PhpParser\Node\Expr\Clone_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiled = $context->getExpressionCompiler()->compile($expr->expr);

        return CompiledExpression::fromZvalValue($compiled->getValue());
    }
}
