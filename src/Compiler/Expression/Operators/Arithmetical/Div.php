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

        switch ($left->getType()) {
            case CompiledExpression::DOUBLE:
            case CompiledExpression::INTEGER:
            case CompiledExpression::BOOLEAN:
                if ($left->isEquals(0)) {
                    $context->notice(
                        'division-zero',
                        sprintf('You trying to use division from %s/{expr}', $left->getValue()),
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
                        sprintf('You trying to use division on {expr}/%s', $right->getValue()),
                        $expr
                    );

                    return new CompiledExpression(CompiledExpression::UNKNOWN);
                }
        }

        switch ($left->getType()) {
            case CompiledExpression::INTEGER:
            case CompiledExpression::DOUBLE:
            case CompiledExpression::BOOLEAN:
                switch ($right->getType()) {
                    case CompiledExpression::BOOLEAN:
                        /**
                         * Boolean is true via isEquals(0) check is not passed before
                         * {int}/1 = {int}
                         * {double}/1 = {double}
                         */

                        $context->notice(
                            'division-on-true',
                            'You trying to use stupid division {expr}/true ~ {expr}/1 = {expr}',
                            $expr
                        );
                    //no break
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                    case CompiledExpression::BOOLEAN:
                        return CompiledExpression::fromZvalValue($left->getValue() / $right->getValue());
                }
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }
}
