<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PHPSA\CompiledExpression;
use PHPSA\Compiler\Parameter;
use PHPSA\Context;
use PhpParser\Node;
use PHPSA\Exception\NotImplementedException;
use PHPSA\Variable;
use ReflectionClass;

class RuntimeClassDefinition extends ParentDefinition
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
        throw new NotImplementedException(__FUNCTION__);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasConst($name)
    {
        throw new NotImplementedException(__FUNCTION__);
    }

    /**
     * @param $name
     * @param boolean|false $inherit
     * @return ClassMethod
     */
    public function getMethod($name, $inherit = false)
    {
        throw new NotImplementedException(__FUNCTION__);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasProperty($name)
    {
        throw new NotImplementedException(__FUNCTION__);
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
