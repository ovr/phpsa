<?php
/**
 * Created by PhpStorm.
 * User: ovr
 * Date: 17.07.15
 * Time: 23:03
 */

namespace PHPSA;


class ScopePointer
{
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
