<?php

namespace Test;

const A = 1;

class UndefinedNsConst
{
  public function success()
  {
    return A;
  }

  public function failed()
  {
    return B;
  }
}
