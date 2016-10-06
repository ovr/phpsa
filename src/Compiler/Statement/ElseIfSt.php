<?php

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class ElseIfSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\ElseIf_';

    /**
     * @param \PhpParser\Node\Stmt\ElseIf_ $statement
     * @param Context $context
     */
    public function compile($elseIfStatement, Context $context)
    {
        $context->getExpressionCompiler()->compile($elseIfStatement->cond);

        if (count($elseIfStatement->stmts) > 0) {
            foreach ($elseIfStatement->stmts as $stmt) {
                \PHPSA\nodeVisitorFactory($stmt, $context);
            }
        } else {
            $context->notice('not-implemented-body', 'Missing body', $elseIfStatement);
        }
    }
}
