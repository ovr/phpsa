<?php

namespace PHPSA\Compiler\Expression\BinaryOp;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PhpParser\Node\Expr\Variable;

class Coalesce extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\Coalesce';

    /**
     * {expr} ?? {expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\Coalesce $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $expressionCompiler = $context->getExpressionCompiler();

        $expressionCompiler->compile($expr->left);
        $expressionCompiler->compile($expr->right);

        return new CompiledExpression();
    }
}
