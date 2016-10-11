<?php

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class GlobalSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Global_';

    /**
     * @param \PhpParser\Node\Stmt\Global_ $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($stmt, Context $context)
    {
        $compiler = $context->getExpressionCompiler();

        foreach ($stmt->vars as $var) {
            $compiler->compile($var);
        }
    }
}
