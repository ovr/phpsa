<?php


namespace Tests\Simple\Undefined;

/**
 * @var int
 */
const A = 1;

/**
 * Class NsConst
 * @package Tests\Simple\Undefined
 */
class NsConst
{
    /**
     * @return int
     */
    public function success()
    {
        return A;
    }

    /**
     * @return mixed
     */
    public function failed()
    {
        return B;
    }
}
