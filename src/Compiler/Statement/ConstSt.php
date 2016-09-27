<?php

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class ConstSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Const_';

    /**
     * @param \PhpParser\Node\Stmt\Const_ $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($stmt, Context $context)
    {
        $compiler = $context->getExpressionCompiler();

        if (count($stmt->consts) > 0) {
            foreach ($stmt->consts as $const) {
                $compiler->compile($const->value);
            }
        }
    }
}
