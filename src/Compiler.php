<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use PHPSA\Definition\ClassDefinition;
use PHPSA\Definition\FunctionDefinition;

class Compiler
{
    /**
     * @var ClassDefinition[]
     */
    protected $classes = array();

    /**
     * @var FunctionDefinition[]
     */
    protected $functions = array();

    public function addClass(ClassDefinition $class)
    {
        $this->classes[] = $class;
    }

    public function addFunction(FunctionDefinition $function)
    {
        $this->functions[] = $function;
    }

    public function compile(Context $context)
    {
        $context->scopePointer = null;

        foreach ($this->functions as $class) {
            $class->compile($context);
        }

        foreach ($this->classes as $class) {
            $class->compile($context);
        }
    }

    /**
     * Try to find function with $namespace from pre-compiled function(s)
     *
     * @param $name
     * @param string|null $namespace
     * @return bool|FunctionDefinition
     */
    public function getFunctionNS($name, $namespace = null)
    {
        foreach ($this->functions as $function) {
            if ($function->getName() == $name && $function->getNamespace() == $namespace) {
                return $function;
            }
        }

        return false;
    }

    /**
     * Try to find function from pre-compiled function(s)
     *
     * @param $name
     * @return bool|FunctionDefinition
     */
    public function getFunction($name)
    {
        foreach ($this->functions as $function) {
            if ($function->getName() == $name) {
                return $function;
            }
        }

        return false;
    }
}
