<?php
/**
 * PHP Static Analysis project 2015
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression\Operators;

use PhpParser\Node\Name;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Types;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class PostDec extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\PostDec';

    /**
     * {expr}++
     *
     * @param \PhpParser\Node\Expr\PostDec $expr
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
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                        $variable->dec();
                        return CompiledExpression::fromZvalValue($variable->getValue());
                }

                $context->notice(
                    'postdec.variable.wrong-type',
                    'You are trying to use post derement operator on variable $' . $variableName .
                    ' with type: ' . $variable->getTypeName(),
                    $expr
                );
            } else {
                $context->notice(
                    'postdec.undefined-variable',
                    'You are trying to use post derement operator on undefined variable: ' . $variableName,
                    $expr
                );
            }

            return new CompiledExpression(CompiledExpression::UNKNOWN);
        }

        $expression = new Expression($context);
        $compiledExpression = $expression->compile($expr->var);

        switch ($compiledExpression->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
                $value = $compiledExpression->getValue();
                return CompiledExpression::fromZvalValue($value++);
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }
}
