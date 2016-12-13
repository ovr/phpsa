<?php

namespace PHPSA\Compiler\Statement;

use PHPSA\Context;

class FinallySt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Finally_';

    /**
     * @param \PhpParser\Node\Stmt\Finally_ $statement
     * @param Context $context
     */
    public function compile($statement, Context $context)
    {
        foreach ($statement->stmts as $stmt) {
            \PHPSA\nodeVisitorFactory($stmt, $context);
        }
    }
}
