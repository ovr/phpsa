<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

class ScopePointer
{
    /**
     * @var Definition\ClassMethod|Definition\FunctionDefinition
     */
    protected $object;

    public function __construct($object)
    {
        if ($object instanceof \PHPSA\Definition\ClassMethod) {
            $this->object = $object;
        }
    }

    /**
     * Is class Method?
     *
     * @return bool
     */
    public function isClassMethod()
    {
        return $this->object instanceof \PHPSA\Definition\ClassMethod;
    }

    /**
     * @return Definition\ClassMethod
     */
    public function getObject()
    {
        return $this->object;
    }
}
