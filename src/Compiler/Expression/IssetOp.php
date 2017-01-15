<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PhpParser\Node\Expr\Variable as VariableNode;

class IssetOp extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Isset_';

    /**
     * isset({expr]})
     *
     * @param \PhpParser\Node\Expr\Isset_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $expressionCompiler = $context->getExpressionCompiler();

        foreach ($expr->vars as $var) {
            $expressionCompiler->compile($var);
        }

        return new CompiledExpression(
            CompiledExpression::BOOLEAN
        );
    }
}
