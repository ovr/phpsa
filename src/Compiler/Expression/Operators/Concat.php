<?php
/**
 * PHP Smart Analysis project 2015-2016
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression\Operators;

use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PHPSA\Context;
use PHPSA\Compiler\Expression;

class Concat extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\Concat';

    /**
     * @param \PhpParser\Node\Expr\BinaryOp\Concat $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $expressionCompiler = $context->getExpressionCompiler();
        $leftExpression = $expressionCompiler->compile($expr->left);
        $rightExpression = $expressionCompiler->compile($expr->right);

        switch ($leftExpression->getType()) {
            case CompiledExpression::ARR:
                $context->notice(
                    'unsupported-operand-types',
                    'Unsupported operand types -{array}',
                    $expr
                );
                return new CompiledExpression(CompiledExpression::UNKNOWN);
        }

        switch ($rightExpression->getType()) {
            case CompiledExpression::ARR:
                $context->notice(
                    'unsupported-operand-types',
                    'Unsupported operand types -{array}',
                    $expr
                );
                return new CompiledExpression(CompiledExpression::UNKNOWN);
        }

        switch ($leftExpression->getType()) {
            case CompiledExpression::STRING:
            case CompiledExpression::NUMBER:
            case CompiledExpression::INTEGER:
            case CompiledExpression::DOUBLE:
            case CompiledExpression::BOOLEAN:
                switch ($rightExpression->getType()) {
                    case CompiledExpression::STRING:
                    case CompiledExpression::NUMBER:
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                    case CompiledExpression::BOOLEAN:
                        return new CompiledExpression(
                            CompiledExpression::STRING,
                            $leftExpression->getValue() . $rightExpression->getValue()
                        );
                        break;
                }
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }
}
