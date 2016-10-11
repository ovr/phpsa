<?php

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class CaseSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Case_';

    /**
     * @param \PhpParser\Node\Stmt\Case_ $statement
     * @param Context $context
     */
    public function compile($statement, Context $context)
    {
        if ($statement->cond) {
            $context->getExpressionCompiler()->compile($statement->cond);
        }

        foreach ($statement->stmts as $caseStatements) {
            \PHPSA\nodeVisitorFactory($caseStatements, $context);
        }
    }
}
