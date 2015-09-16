<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Statement;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\StatementCompilerInterface;
use RuntimeException;

abstract class AbstractCompiler implements StatementCompilerInterface
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
     * @param $stmt
     * @param Context $context
     * @return CompiledExpression
     */
    public function pass($stmt, Context $context)
    {
        $this->assertExpression($stmt);
        return $this->compile($stmt, $context);
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
