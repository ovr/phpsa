<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class SwitchSt extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Switch_';

    /**
     * @param \PhpParser\Node\Stmt\Switch_ $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($stmt, Context $context)
    {
        $context->getExpressionCompiler()->compile($stmt->cond);

        if (count($stmt->cases)) {
            foreach ($stmt->cases as $case) {
                if ($case->cond) {
                    $context->getExpressionCompiler()->compile($case->cond);
                }

                if (count($case->stmts)) {
                    foreach ($case->stmts as $caseStatements) {
                        \PHPSA\nodeVisitorFactory($caseStatements, $context);
                    }
                }
            }
        } else {
            $context->notice('switch.empty', 'Switch block is empty, lol', $stmt);
        }
    }
}
