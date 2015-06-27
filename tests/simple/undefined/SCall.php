<?php

namespace Tests\Simple\Undefined;

class UndefinedScall
{
    /**
     * @return mixed
     */
    static public function a()
    {
        return self::b();
    }

    /**
     * @return mixed
     */
    static public function c()
    {
        return self::a();
    }
}
