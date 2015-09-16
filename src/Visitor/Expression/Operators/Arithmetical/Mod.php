<?php
/**
 * PHP Static Analysis project 2015
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor\Expression\Operators\Arithmetical;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Visitor\Expression;
use PHPSA\Visitor\Expression\AbstractExpressionCompiler;

class Mod extends AbstractExpressionCompiler
{
    protected $name = '\PhpParser\Node\Expr\BinaryOp\Mod';

    /**
     * {expr} % {expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\Mod $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $expression = new Expression($context);
        $left = $expression->compile($expr->left);

        $expression = new Expression($context);
        $right = $expression->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::BOOLEAN:
            case CompiledExpression::DNUMBER:
                if ($left->isEquals(0)) {
                    $context->notice(
                        'division-zero',
                        'You trying to use division from ' . $left->getValue() . '%{expr}',
                        $expr
                    );
                }
                break;
        }

        switch ($right->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
            case CompiledExpression::BOOLEAN:
                if ($right->isEquals(0)) {
                    $context->notice(
                        'division-zero',
                        'You trying to use division on {expr}%' . $right->getValue(),
                        $expr
                    );

                    return new CompiledExpression(CompiledExpression::UNKNOWN);
                }
                break;
        }

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
            case CompiledExpression::BOOLEAN:
                switch ($right->getType()) {
                    case CompiledExpression::BOOLEAN:
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                        return CompiledExpression::fromZvalValue($left->getValue() % $right->getValue());
                }
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }
}
