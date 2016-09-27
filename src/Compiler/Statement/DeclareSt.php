<?php

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class DeclareSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Declare_';

    /**
     * @param \PhpParser\Node\Stmt\Declare_ $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($stmt, Context $context)
    {
        $compiler = $context->getExpressionCompiler();

        if (count($stmt->declares) > 0) {
            foreach ($stmt->declares as $declare) {
                $compiler->compile($declare->value);
            }
        }

        if (count($stmt->stmts) > 0) {
            foreach ($stmt->stmts as $stmt) {
                \PHPSA\nodeVisitorFactory($stmt, $context);
            }
        }
    }
}
