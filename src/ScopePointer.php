<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use PHPSA\Definition\ClassMethod;
use PHPSA\Definition\FunctionDefinition;

class ScopePointer
{
    /**
     * @var ClassMethod|FunctionDefinition
     */
    protected $object;

    public function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * Is class Method?
     *
     * @return bool
     */
    public function isClassMethod()
    {
        return $this->object instanceof ClassMethod;
    }

    /**
     * Is class Method?
     *
     * @return bool
     */
    public function isFunction()
    {
        return $this->object instanceof FunctionDefinition;
    }

    /**
     * @return ClassMethod|FunctionDefinition
     */
    public function getObject()
    {
        return $this->object;
    }
}
