<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PHPSA\Context;
use PhpParser\Node;

/**
 * Class ClassDefinition
 * @package PHPSA\Definition
 */
class ClassDefinition extends AbstractDefinition
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * Class methods
     *
     * @var ClassMethod[]
     */
    protected $methods = array();

    /**
     * Class properties
     *
     * @var Node\Stmt\Property[]
     */
    protected $properties = array();

    /**
     * Class constants
     *
     * @var Node\Stmt\Const_[]
     */
    protected $constants = array();

    /**
     * @todo Use Finder
     *
     * @var string
     */
    protected $filepath;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param ClassMethod $methodDefintion
     */
    public function addMethod(ClassMethod $methodDefintion)
    {
        $this->methods[$methodDefintion->getName()] = $methodDefintion;
    }

    /**
     * @param Node\Stmt\Property $property
     */
    public function addProperty(Node\Stmt\Property $property)
    {
        $this->properties[$property->props[0]->name] = $property;
    }

    /**
     * @param Node\Stmt\ClassConst $const
     */
    public function addConst(Node\Stmt\ClassConst $const)
    {
        $this->constants[$const->consts[0]->name] = $const;
    }

    /**
     * Compile methods to check it
     *
     * @param Context $context
     */
    public function compile(Context $context)
    {
        $context->setScope($this);

        foreach ($this->methods as $method) {
            $context->clearSymbols();
            $method->compile($context);

            $symbols = $context->getSymbols();
            if (count($symbols) > 0) {
                foreach ($symbols as $name => $variable) {
                    /**
                     * Check if you are setting values to variable but didn't use it (mean get)
                     */
                    if ($variable->getGets() == 0 && $variable->incSets()) {
                        $context->warning(
                            'unused-variable',
                            sprintf('Unused variable $%s in method %s()', $variable->getName(), $method->getName())
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

    /**
     * @param $name
     * @return bool
     */
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
