<?php

namespace Tests\Simple\Undefined;

class Property
{
    /**
     * @var string
     */
    protected $b = "test string";

    /**
     * @return mixed
     */
    public function a()
    {
        return $this->a;
    }

    /**
     * @return string
     */
    public function b()
    {
        return $this->b;
    }
}
