<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\ScopePointer;
use PHPSA\Variable;

abstract class AbstractDefinition
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Compile the definition
     *
     * @param Context $context
     * @return boolean
     */
    abstract public function compile(Context $context);

    /**
     * @var bool
     */
    protected $compiled = false;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return ScopePointer
     */
    public function getPointer()
    {
        return new ScopePointer($this);
    }

    /**
     * @return boolean
     */
    public function isCompiled()
    {
        return $this->compiled;
    }
}
