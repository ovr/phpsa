<?php

class UndefinedScall
{
  static public function a()
  {
    return self::b();
  }

  static public function c()
  {
    return self::a();
  }
}
