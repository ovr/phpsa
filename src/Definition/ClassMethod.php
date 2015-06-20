<?php

namespace PHPSA\Definition;

class ClassMethod
{
    /**
     * @var string
     */
    protected $name;

    protected $ast;

    public function __construct($name, $ast)
    {
        $this->name = $name;
        $this->ast = $ast;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
