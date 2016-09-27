<?php

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class BreakSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Break_';

    /**
     * @param \PhpParser\Node\Stmt\Break_ $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($stmt, Context $context)
    {
        $compiler = $context->getExpressionCompiler();

        if ($stmt->num !== null) {
            $compiler->compile($stmt->num);
        }
    }
}
