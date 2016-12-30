<?php

namespace PHPSA\Compiler\Expression\Operators\Bitwise;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class BitwiseNot extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BitwiseNot';

    /**
     * ~{expr}
     *
     * @param \PhpParser\Node\Expr\BitwiseNot $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiledExpression = $context->getExpressionCompiler()->compile($expr->expr);
        if ($compiledExpression->isCorrectValue()) {
            switch ($compiledExpression->getType()) {
                case CompiledExpression::NUMBER:
                case CompiledExpression::INTEGER:
                case CompiledExpression::DOUBLE:
                case CompiledExpression::STRING:
                    return CompiledExpression::fromZvalValue(~$compiledExpression->getValue());
            }
        }

        return new CompiledExpression();
    }
}
