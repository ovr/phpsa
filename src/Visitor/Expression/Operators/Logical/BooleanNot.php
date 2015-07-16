<?php

namespace PHPSA\Visitor\Expression\Operators\Logical;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Visitor\Expression;
use PHPSA\Visitor\Expression\AbstractExpressionCompiler;

class BooleanNot extends AbstractExpressionCompiler
{
    protected $name = '\PhpParser\Node\Expr\BooleanNot';

    /**
     * !{expr}
     *
     * @param \PhpParser\Node\Expr\BooleanNot $expr
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($expr, Context $context)
    {
        $expression = new Expression($context);
        $compiledExpression = $expression->compile($expr->expr);

        switch ($compiledExpression->getType()) {
            case CompiledExpression::DNUMBER:
            case CompiledExpression::LNUMBER:
            case CompiledExpression::STRING:
            case CompiledExpression::BOOLEAN:
                return new CompiledExpression(CompiledExpression::BOOLEAN, !$compiledExpression->getValue());
        }


        return new CompiledExpression();
    }
}
