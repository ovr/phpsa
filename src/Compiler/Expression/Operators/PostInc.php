<?php
/**
 * PHP Smart Analysis project 2015-2016
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression\Operators;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class PostInc extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\PostInc';

    /**
     * {expr}++
     *
     * @param \PhpParser\Node\Expr\PostInc $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        if ($expr->var instanceof \PHPParser\Node\Expr\Variable) {
            $variableName = (string)$expr->var->name;
            $variable = $context->getSymbol($variableName);
            if ($variable) {
                $variable->incUse();

                switch ($variable->getType()) {
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                    case CompiledExpression::NUMBER:
                        $variable->inc();
                        return CompiledExpression::fromZvalValue($variable->getValue());
                }

                $context->notice(
                    'language_error',
                    'You are trying to use post increment operator on variable $' . $variableName .
                    ' with type: ' . $variable->getTypeName(),
                    $expr
                );
            } else {
                $context->notice(
                    'language_error',
                    'You are trying to use post increment operator on undefined variable: ' . $variableName,
                    $expr
                );
            }
        }

        return new CompiledExpression();
    }
}
