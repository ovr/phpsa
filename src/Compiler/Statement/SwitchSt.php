<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;

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

                if (count($case->stmts) > 0) {
                    $beforeStatement = false;

                    foreach ($case->stmts as $stmt) {
                        \PHPSA\nodeVisitorFactory($stmt, $context);

                        if ($beforeStatement) {
                            if ($beforeStatement instanceof \PhpParser\Node\Stmt\Return_
                                && $stmt instanceof \PhpParser\Node\Stmt\Break_) {
                                $context->notice(
                                    'switch.unneeded-break',
                                    'Break after return statement is not needed',
                                    $stmt
                                );
                            }
                        }
                        $beforeStatement = $stmt;
                    }
                }
            }
        } else {
            $context->notice('switch.empty', 'Switch block is empty, lol', $stmt);
        }
    }
}
