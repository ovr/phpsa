<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PHPSA\Context;

class ClassDefinition
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $name;

    /**
     * Class methods
     *
     * @var ClassMethod[]
     */
    protected $methods = [];

    /**
     * Class properties
     *
     * @var \PhpParser\Node\Stmt\Property[]
     */
    protected $properties = [];

    /**
     * Class constants
     *
     * @var \PhpParser\Node\Stmt\Const_[]
     */
    protected $constants = [];

    /**
     * @todo Use Finder
     *
     * @var string
     */
    protected $filepath;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function addMethod(ClassMethod $methodDefintion)
    {
        $this->methods[$methodDefintion->getName()] = $methodDefintion;
    }

    public function addProperty(\PhpParser\Node\Stmt\Property $property)
    {
        $this->properties[$property->props[0]->name] = $property;
    }

    public function addConst(\PhpParser\Node\Stmt\ClassConst $const)
    {
        $this->constants[$const->consts[0]->name] = $const;
    }

    /**
     * Mean check file....,
     *
     * Compile methods to check it
     */
    public function compile(Context $context)
    {
        foreach ($this->methods as $method) {
            $context->clearSymbols();
            $method->compile($context);

//            $context->dump();

            $symbols = $context->getSymbols();
            if (count($symbols) > 0) {
                foreach ($symbols as $name => $variable) {
                    if ($variable->getGets() == 0 && $variable->incSets()) {
                        $context->notice(
                            'unused-variable',
                            sprintf('Unused variable %s in method %s()', $variable->getName(), $method->getName()),
                            null
                        );
                    }
                }
            }
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasMethod($name)
    {
        return isset($this->methods[$name]);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasConst($name)
    {
        return isset($this->constants[$name]);
    }

    /**
     * @param $name
     * @return ClassMethod
     */
    public function getMethod($name)
    {
        return $this->methods[$name];
    }

    public function hasProperty($name)
    {
        return isset($this->properties[$name]);
    }

    /**
     * @return string
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * @param string $filepath
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
    }
}
