<?php

class UndefinedVar
{
  public function test()
  {
      $a = 1;
      return $a + $b;
  }
}
