<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Visitor\ExpressionCompilerInterface;
use RuntimeException;

abstract class AbstractExpressionCompiler implements ExpressionCompilerInterface
{
    /**
     * @abstract
     * @var string
     */
    protected $name = 'unknown';

    protected function assertExpression($expression)
    {
        if (!$expression instanceof $this->name) {
            throw new RuntimeException('Passed $expression must be instance of ' . $this->name);
        }
    }

    /**
     * @param  $expr
     * @param Context $context
     * @return CompiledExpression
     */
    public function pass($expr, Context $context)
    {
        $this->assertExpression($expr);
        return $this->compile($expr, $context);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $expr
     * @param Context $context
     * @return mixed
     */
    abstract protected function compile($expr, Context $context);
}
