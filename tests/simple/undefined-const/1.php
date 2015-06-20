<?php

class UndefinedConst
{
  const A = 1;

  public function testA()
  {
    return self::A;
  }
}
