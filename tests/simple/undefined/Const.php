<?php

namespace Tests\Simple\Undefined;

class UndefinedConst
{
  const A = 1;

  public function testA()
  {
    return self::A;
  }

  public function testUndefinedConst()
  {
    return self::BBBB;
  }
}
