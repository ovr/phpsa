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

    /**
     * Initializes the scopePointer with an object
     *
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * Is the object a class method?
     *
     * @return bool
     */
    public function isClassMethod()
    {
        return $this->object instanceof ClassMethod;
    }

    /**
     * Is the object a function?
     *
     * @return bool
     */
    public function isFunction()
    {
        return $this->object instanceof FunctionDefinition;
    }

    /**
     * Returns the object.
     *
     * @return ClassMethod|FunctionDefinition
     */
    public function getObject()
    {
        return $this->object;
    }
}
