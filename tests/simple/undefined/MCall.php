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
}
