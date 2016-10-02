<?php

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class StaticSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Static_';

    /**
     * @param \PhpParser\Node\Stmt\Static_ $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($stmt, Context $context)
    {
        $compiler = $context->getExpressionCompiler();

        if (count($stmt->vars) > 0) {
            foreach ($stmt->vars as $var) {
                $compiler->compile($var->default);
            }
        }
    }
}
