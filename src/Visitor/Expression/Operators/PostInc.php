<?php

namespace PHPSA\Visitor\Expression\Operators;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Visitor\Expression;
use PHPSA\Visitor\Expression\AbstractExpressionCompiler;

class PostInc extends AbstractExpressionCompiler
{
    protected $name = '\PhpParser\Node\Expr\PostInc';

    /**
     * {expr} && {expr}
     *
     * @param \PhpParser\Node\Expr\PostInc $expr
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($expr, Context $context)
    {
        $expression = new Expression($context);
        $left = $expression->compile($expr->var);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
                $value = $left->getValue();
                return CompiledExpression::fromZvalValue($value++);
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }
}
