<?php

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class ElseSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Else_';

    /**
     * @param \PhpParser\Node\Stmt\Else_ $statement
     * @param Context $context
     */
    public function compile($elseStatement, Context $context)
    {
        if (count($elseStatement->stmts) > 0) {
            foreach ($elseStatement->stmts as $stmt) {
                \PHPSA\nodeVisitorFactory($stmt, $context);
            }
        } else {
            $context->notice('not-implemented-body', 'Missing body', $elseStatement);
        }
    }
}
