<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Variable;

class CatchSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Catch_';

    /**
     * @param \PhpParser\Node\Stmt\Catch_ $statement
     * @param Context $context
     */
    public function compile($statement, Context $context)
    {
        $context->addVariable(new Variable($statement->var, null, CompiledExpression::OBJECT));

        foreach ($statement->stmts as $stmt) {
            \PHPSA\nodeVisitorFactory($stmt, $context);
        }
    }
}
