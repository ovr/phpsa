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
     * @var string
     */
    protected $namespace;

    abstract public function compile(Context $context);

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
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }
}
