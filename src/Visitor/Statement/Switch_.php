<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Definition\ClassMethod;
use PHPSA\Visitor\Expression;

class Switch_ extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Switch_';

    /**
     * @param \PhpParser\Node\Stmt\Switch_ $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($stmt, Context $context)
    {
        $expression = new Expression($context);
        $expression->compile($stmt->cond);

        if (count($stmt->cases)) {
            foreach ($stmt->cases as $case) {
                if ($case->cond) {
                    $expression = new Expression($context);
                    $expression->compile($case->cond);
                }

                if (count($case->stmts) > 0) {
                    foreach ($case->stmts as $stmt) {
                        \PHPSA\nodeVisitorFactory($stmt, $context);
                    }
                } else {
                    $context->notice('not-implemented-body', 'Missing body', $case);
                }
            }
        } else {
            //@todo implement
        }
    }
}
