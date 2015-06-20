<?php

namespace PHPSA\Definition;

class ClassMethod
{
    /**
     * @var string
     */
    protected $name;

    public function __construct($name)
    {

    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
