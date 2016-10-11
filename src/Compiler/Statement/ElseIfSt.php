<?php

namespace PHPSA\Compiler\Statement;

use PHPSA\Context;

class ElseIfSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\ElseIf_';

    /**
     * @param \PhpParser\Node\Stmt\ElseIf_ $elseIfStatement
     * @param Context $context
     */
    public function compile($elseIfStatement, Context $context)
    {
        $context->getExpressionCompiler()->compile($elseIfStatement->cond);

        foreach ($elseIfStatement->stmts as $stmt) {
            \PHPSA\nodeVisitorFactory($stmt, $context);
        }
    }
}
