<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class ExitOp extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Exit_';

    /**
     * exit({expr})
     *
     * @param \PhpParser\Node\Expr\Exit_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiled = $context->getExpressionCompiler()->compile($expr->expr);

        return CompiledExpression::fromZvalValue($compiled->getValue());
    }
}
