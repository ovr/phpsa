<?php

namespace PHPSA\Visitor\Expression\Operators\Comparison;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Visitor\Expression;
use PHPSA\Visitor\Expression\AbstractExpressionCompiler;

class Greater extends AbstractExpressionCompiler
{
    protected $name = '\PhpParser\Node\Expr\BinaryOp\Greater';

    /**
     * {expr} > {expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\Greater $expr
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($expr, Context $context)
    {
        $expression = new Expression($context);
        $left = $expression->compile($expr->left);

        $expression = new Expression($context);
        $right = $expression->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                        return new CompiledExpression(CompiledExpression::BOOLEAN, $left->getValue() > $right->getValue());
                }
                break;
        }

        return new CompiledExpression();
    }
}
