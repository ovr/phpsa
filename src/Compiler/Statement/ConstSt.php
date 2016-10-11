<?php

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class ConstSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Const_';

    /**
     * @param \PhpParser\Node\Stmt\Const_ $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($stmt, Context $context)
    {
        $compiler = $context->getExpressionCompiler();

        foreach ($stmt->consts as $const) {
            $compiler->compile($const->value);
        }
    }
}
