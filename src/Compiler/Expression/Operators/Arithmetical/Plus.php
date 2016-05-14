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

class Plus extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\Plus';

    /**
     * {expr} + {expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\Plus $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $left = $context->getExpressionCompiler()->compile($expr->left);
        $right = $context->getExpressionCompiler()->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::INTEGER:
                switch ($right->getType()) {
                    case CompiledExpression::INTEGER:
                        /**
                         * php -r "var_dump(1 + 1);" int(2)
                         */
                        return new CompiledExpression(CompiledExpression::INTEGER, $left->getValue() + $right->getValue());
                    case CompiledExpression::DOUBLE:
                        /**
                         * php -r "var_dump(1 + 1.0);" double(2)
                         */
                        return new CompiledExpression(CompiledExpression::DOUBLE, $left->getValue() + $right->getValue());
                }
                break;
            case CompiledExpression::DOUBLE:
                switch ($right->getType()) {
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                        /**
                         * php -r "var_dump(1.0 + 1);"   double(2)
                         * php -r "var_dump(1.0 + 1.0);" double(2)
                         */
                        return new CompiledExpression(CompiledExpression::DOUBLE, $left->getValue() + $right->getValue());
                }
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }
}
