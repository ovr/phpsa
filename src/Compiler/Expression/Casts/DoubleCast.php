<?php

namespace PHPSA\Compiler\Expression\Casts;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class DoubleCast extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Cast\Double';

    /**
     * (double) {$expr}
     *
     * @param \PhpParser\Node\Expr\Cast\Double $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiledExpression = $context->getExpressionCompiler()->compile($expr->expr);

        switch ($compiledExpression->getType()) {
            case CompiledExpression::BOOLEAN:
            case CompiledExpression::DOUBLE:
            case CompiledExpression::INTEGER:
            case CompiledExpression::NUMBER:
            case CompiledExpression::STRING:
            case CompiledExpression::ARR:
            case CompiledExpression::OBJECT:
                return new CompiledExpression(CompiledExpression::DOUBLE, (double) $compiledExpression->getValue());
        }

        return new CompiledExpression();
    }
}
