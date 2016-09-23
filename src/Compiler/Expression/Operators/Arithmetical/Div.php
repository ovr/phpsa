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

class Div extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\Div';

    /**
     * {expr} / {expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\Div $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $left = $context->getExpressionCompiler()->compile($expr->left);
        $right = $context->getExpressionCompiler()->compile($expr->right);

        if ($left->isEquals(0)) {
            $context->notice(
                'division-zero',
                sprintf('You are trying to divide from 0: %s/{expr}', $left->getValue()),
                $expr
            );
        }

        if ($right->isEquals(0)) {
            $context->notice(
                'division-zero',
                sprintf('You are trying to divide by 0: {expr}/%s', $right->getValue()),
                $expr
            );

            return new CompiledExpression(CompiledExpression::UNKNOWN);
        }

        switch ($left->getType()) {
            case CompiledExpression::INTEGER:
            case CompiledExpression::DOUBLE:
            case CompiledExpression::BOOLEAN:
                switch ($right->getType()) {
                    case CompiledExpression::BOOLEAN:
                        /**
                         * Boolean is true since isEquals(0) check did not pass before
                         * {expr}/1 = {expr}
                         */

                        $context->notice(
                            'division-by-true',
                            'You are trying to divide by true: {expr}/true ~ {expr}/1 = {expr}',
                            $expr
                        );
                    //no break
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                    case CompiledExpression::BOOLEAN:
                        return CompiledExpression::fromZvalValue($left->getValue() / $right->getValue());
                }
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }
}
