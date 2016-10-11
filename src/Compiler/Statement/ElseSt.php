<?php

namespace PHPSA\Compiler\Statement;

use PHPSA\Context;

class ElseSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Else_';

    /**
     * @param \PhpParser\Node\Stmt\Else_ $elseStatement
     * @param Context $context
     */
    public function compile($elseStatement, Context $context)
    {
        foreach ($elseStatement->stmts as $stmt) {
            \PHPSA\nodeVisitorFactory($stmt, $context);
        }
    }
}
