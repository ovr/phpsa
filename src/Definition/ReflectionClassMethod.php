<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use ReflectionMethod;

/**
 * Class Method created from Reflection
 */
class ReflectionClassMethod extends ClassMethod
{
    /**
     * Return type
     *
     * @var int
     */
    protected $returnType = CompiledExpression::VOID;

    /**
     * Array of possible return values
     *
     * @var array
     */
    protected $possibleReturnValues = [];

    /**
     * @var ReflectionMethod
     */
    protected $reflection;

    /**
     * ReflectionClassMethod constructor.
     * @param ReflectionMethod $reflection
     */
    public function __construct(ReflectionMethod $reflection)
    {
        $this->reflection = $reflection;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->reflection->getName();
    }

    /**
     * @param Context $context
     * @return ReflectionClassMethod
     */
    public function compile(Context $context)
    {
        $this->compiled = true;
        $context->scopePointer = $this->getPointer();

        return $this;
    }

    /**
     * @return bool
     */
    public function isAbstract()
    {
        return $this->reflection->isAbstract();
    }

    /**
     * @return bool
     */
    public function isStatic()
    {
        return $this->reflection->isStatic();
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->reflection->isPublic();
    }

    /**
     * @return bool
     */
    public function isProtected()
    {
        return $this->reflection->isProtected();
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->reflection->isPrivate();
    }
}
