<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use PHPSA\Definition\ClassDefinition;
use PHPSA\Definition\FunctionDefinition;
use PHPSA\Definition\RuntimeClassDefinition;
use ReflectionClass;

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

    /**
     * @param ClassDefinition $class
     */
    public function addClass(ClassDefinition $class)
    {
        $this->classes[implode('\\', [$class->getNamespace(), $class->getName()])] = $class;
    }

    /**
     * @param FunctionDefinition $function
     */
    public function addFunction(FunctionDefinition $function)
    {
        $this->functions[] = $function;
    }

    /**
     * @param Context $context
     */
    public function compile(Context $context)
    {
        $context->scopePointer = null;

        /**
         * @todo Implement class map...
         */
        foreach ($this->classes as $class) {
            $extends = $class->getExtendsClass();
            if ($extends) {
                if (isset($this->classes[$extends])) {
                    $class->setExtendsClassDefinition($this->classes[$extends]);
                } else {
                    if (class_exists($extends, true)) {
                        $class->setExtendsClassDefinition(
                            new RuntimeClassDefinition(
                                new ReflectionClass(
                                    $extends
                                )
                            )
                        );
                    }
                }
            }
        }

        foreach ($this->functions as $function) {
            /**
             * @todo Configuration
             *
             * Ignore functions compiling from vendor
             */
            $checkVendor = strpos($function->getFilepath(), './vendor');
            if ($checkVendor !== false && $checkVendor < 3) {
                continue;
            }

            $function->compile($context);
        }

        foreach ($this->classes as $class) {
            /**
             * @todo Configuration
             *
             * Ignore Classes compiling from vendor
             */
            $checkVendor = strpos($class->getFilepath(), './vendor');
            if ($checkVendor !== false && $checkVendor < 3) {
                continue;
            }

            $class->compile($context);
        }
    }

    /**
     * Try to find function with $namespace from pre-compiled function(s)
     *
     * @param string $name
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
     * @param string $name
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
