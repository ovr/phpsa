<?php

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PhpParser\Node\Scalar\LNumber;

class BreakSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Break_';

    /**
     * @param \PhpParser\Node\Stmt\Break_ $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($stmt, Context $context)
    {
        $compiler = $context->getExpressionCompiler();

        if ($stmt->num !== null) {
            $compiled = $compiler->compile($stmt->num);
            
            if (!($stmt->num instanceof LNumber) || $compiled->getValue() == 0) {
                $context->notice(
                    'language-error',
                    'Break only supports positive integers.',
                    $stmt
                );
            }
        }
    }
}
