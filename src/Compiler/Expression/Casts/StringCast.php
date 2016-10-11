<?php

namespace PHPSA\Compiler\Expression\Casts;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class StringCast extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Cast\String_';

    /**
     * (string) {$expr}
     *
     * @param \PhpParser\Node\Expr\Cast\String_ $expr
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
            case CompiledExpression::RESOURCE:
            case CompiledExpression::NULL:
                return new CompiledExpression(CompiledExpression::STRING, (string) $compiledExpression->getValue());
        }

        return new CompiledExpression();
    }
}
