<?php


namespace Tests\Simple\Undefined;

const A = 1;

class NsConst
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
