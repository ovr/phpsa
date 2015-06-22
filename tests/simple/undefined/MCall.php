<?php

class Test
{
  public function a()
  {
    return $this->b();
  }

  public function c()
  {
    return $this->a();
  }

  public function testSuccessProperty()
  {
    $property = new Property();
    return $property->b();
  }

  public function testCallFromUnusedVariable()
  {
    return $unusedVariable->b();
  }
}
