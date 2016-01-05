<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PHPSA\Context;
use PHPSA\Exception\NotImplementedException;
use ReflectionClass;

class RuntimeClassDefinition extends ClassDefinition
{
    /**
     * @var ReflectionClass
     */
    protected $reflection;

    /**
     * @param ReflectionClass $reflection
     */
    public function __construct(ReflectionClass $reflection)
    {
        $this->reflection = $reflection;
    }

    /**
     * @param Context $context
     * @return $this
     */
    public function compile(Context $context)
    {
        return $this;
    }

    /**
     * @param string $name
     * @param boolean|false $inherit
     * @return bool
     */
    public function hasMethod($name, $inherit = false)
    {
        return $this->reflection->hasMethod($name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasConst($name)
    {
        return $this->reflection->hasConstant($name);
    }

    /**
     * @param $name
     * @param boolean|false $inherit
     * @return ReflectionClassMethod
     */
    public function getMethod($name, $inherit = false)
    {
        return new ReflectionClassMethod($this->reflection->getMethod($name));
    }

    /**
     * @param $name
     * @param bool $inherit
     * @return bool
     */
    public function hasProperty($name, $inherit = false)
    {
        return $this->reflection->hasProperty($name);
    }

    /**
     * @return string
     */
    public function getFilepath()
    {
        throw new NotImplementedException(__FUNCTION__);
    }

    /**
     * @return bool
     */
    public function isAbstract()
    {
        throw new NotImplementedException(__FUNCTION__);
    }

    /**
     * @return null|ClassDefinition
     */
    public function getExtendsClassDefinition()
    {
        throw new NotImplementedException(__FUNCTION__);
    }

    /**
     * @return null|string
     */
    public function getExtendsClass()
    {
        throw new NotImplementedException(__FUNCTION__);
    }
}
