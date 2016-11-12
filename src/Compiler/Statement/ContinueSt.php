<?php

namespace PHPSA\Compiler\Statement;

use PhpParser\Node\Scalar\LNumber;
use PHPSA\CompiledExpression;
use PHPSA\Context;

class ContinueSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Continue_';

    /**
     * @param \PhpParser\Node\Stmt\Continue_ $stmt
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
                    'Continue only supports positive integers.',
                    $stmt
                );
            }
        }
    }
}
