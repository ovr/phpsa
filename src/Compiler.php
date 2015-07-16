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

    public function compile($context)
    {
        foreach ($this->functions as $class) {
            $class->compile($context);
        }

        foreach ($this->classes as $class) {
            $class->compile($context);
        }
    }
}
