<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class ShellExec extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\ShellExec';

    /**
     * `{expr}`
     *
     * @param \PhpParser\Node\Expr\ShellExec $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        return CompiledExpression::fromZvalValue(null);
    }
}
