<?php
/**
 * PHP Smart Analysis project 2015-2016
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression\Operators\Arithmetical;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class Mod extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\Mod';

    /**
     * {expr} % {expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\Mod $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $left = $context->getExpressionCompiler()->compile($expr->left);
        $right = $context->getExpressionCompiler()->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::INTEGER:
            case CompiledExpression::BOOLEAN:
            case CompiledExpression::DOUBLE:
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
            case CompiledExpression::INTEGER:
            case CompiledExpression::DOUBLE:
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
            case CompiledExpression::INTEGER:
            case CompiledExpression::DOUBLE:
            case CompiledExpression::BOOLEAN:
                switch ($right->getType()) {
                    case CompiledExpression::BOOLEAN:
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                        return CompiledExpression::fromZvalValue($left->getValue() % $right->getValue());
                }
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }
}
