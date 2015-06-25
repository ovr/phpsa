<?php

namespace Tests\Simple\Undefined;

class Property
{
  protected $b = "test string";

  public function a()
  {
      return $this->a;
  }

  public function b()
  {
      return $this->b;
  }
}
