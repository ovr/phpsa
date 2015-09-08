<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Definition\ClassMethod;
use PHPSA\Visitor\Expression;

class While_ extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\While_';

    /**
     * @param \PhpParser\Node\Stmt\While_ $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($stmt, Context $context)
    {
        $expression = new Expression($context);
        $expression->compile($stmt->cond);

        if (count($stmt->stmts) > 0) {
            foreach ($stmt->stmts as $statement) {
                \PHPSA\nodeVisitorFactory($statement, $context);
            }
        } else {
            $context->notice('not-implemented-body', 'Missing body', $stmt);
        }
    }
}
