<?php
/**
 * PHP Smart Analysis project 2015-2016
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression\Operators;

use PhpParser\Node\Name;
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
            $variableName = $expr->var->name;
            if ($variableName instanceof Name) {
                $variableName = $variableName->parts[0];
            }

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
                    'postinc.variable.wrong-type',
                    'You are trying to use post increment operator on variable $' . $variableName .
                    ' with type: ' . $variable->getTypeName(),
                    $expr
                );
            } else {
                $context->notice(
                    'postinc.undefined-variable',
                    'You are trying to use post increment operator on undefined variable: ' . $variableName,
                    $expr
                );
            }

            return new CompiledExpression(CompiledExpression::UNKNOWN);
        }

        $compiledExpression = $context->getExpressionCompiler()->compile($expr->var);

        switch ($compiledExpression->getType()) {
            case CompiledExpression::INTEGER:
            case CompiledExpression::DOUBLE:
            case CompiledExpression::NUMBER:
                $value = $compiledExpression->getValue();
                return CompiledExpression::fromZvalValue($value++);
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }
}
