<?php
/**
 * PHP Static Analysis project 2015
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression\AssignOp;

use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PHPSA\Context;

class Pow extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\AssignOp\Pow';

    /**
     * It's used in conditions
     * {left-expr} **= {right-expr}
     *
     * @param \PhpParser\Node\Expr\AssignOp\Pow $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $expression = new Expression($context);
        $left = $expression->compile($expr->var);

        $expression = new Expression($context);
        $expression->compile($expr->expr);

        return $left;
    }
}
