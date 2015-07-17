<?php
/**
 * PHP Static Analysis project 2015
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor\Expression\Operators;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Visitor\Expression;
use PHPSA\Visitor\Expression\AbstractExpressionCompiler;

class PostInc extends AbstractExpressionCompiler
{
    protected $name = '\PhpParser\Node\Expr\PostInc';

    /**
     * {expr}++
     *
     * @param \PhpParser\Node\Expr\PostInc $expr
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($expr, Context $context)
    {
        if ($expr->var instanceof \PHPParser\Node\Expr\Variable) {
            $name = $expr->var->name;

            $variable = $context->getSymbol($name);
            if ($variable) {
                $variable->inc();
                return CompiledExpression::fromZvalValue($variable->getValue());
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
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }
}
