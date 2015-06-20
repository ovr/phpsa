<?php

namespace PHPSA\Definition;

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

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function addMethod(ClassMethod $methodDefintion)
    {
        $this->methods[$methodDefintion->getName()] = $methodDefintion;
    }

    /**
     * Mean check file....,
     *
     * Compile methods to check it
     */
    public function compile()
    {
        foreach ($this->methods as $method) {
            var_dump($method);
            die();
        }
    }
}
