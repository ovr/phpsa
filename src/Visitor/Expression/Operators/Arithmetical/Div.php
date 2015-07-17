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

class Div extends AbstractExpressionCompiler
{
    protected $name = '\PhpParser\Node\Expr\BinaryOp\Div';

    /**
     * {expr} / {expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\Div $expr
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
            case CompiledExpression::DNUMBER:
                if ($left->isEquals(0)) {
                    $context->notice(
                        'division-zero',
                        sprintf('You trying to use division from %s/{expr}', $left->getValue()),
                        $expr
                    );

                    return new CompiledExpression(CompiledExpression::DNUMBER, 0.0);
                }
                break;
            case CompiledExpression::LNUMBER:
            case CompiledExpression::BOOLEAN:
                if ($left->isEquals(0)) {
                    $context->notice(
                        'division-zero',
                        sprintf('You trying to use division from %s/{expr}', $left->getValue()),
                        $expr
                    );

                    switch ($right->getType()) {
                        case CompiledExpression::LNUMBER:
                        case CompiledExpression::BOOLEAN:
                            return new CompiledExpression(CompiledExpression::LNUMBER, 0);
                        case CompiledExpression::DNUMBER:
                            return new CompiledExpression(CompiledExpression::DNUMBER, 0.0);
                    }
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
                        sprintf('You trying to use division on {expr}/%s', $right->getValue()),
                        $expr
                    );

                    return new CompiledExpression(CompiledExpression::UNKNOWN);
                }
        }

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
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
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                    case CompiledExpression::BOOLEAN:
                        return CompiledExpression::fromZvalValue($left->getValue() / $right->getValue());
                }
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }
}
