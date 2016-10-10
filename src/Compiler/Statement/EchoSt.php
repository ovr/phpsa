<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class EchoSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Echo_';

    /**
     * @param \PhpParser\Node\Stmt\Echo_ $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($stmt, Context $context)
    {
        $compiler = $context->getExpressionCompiler();

        foreach ($stmt->exprs as $expr) {
            $compiler->compile($expr);
        }
    }
}
