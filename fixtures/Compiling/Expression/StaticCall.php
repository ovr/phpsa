<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace Tests\Compiling\Statements;

class StaticCall
{
    /**
     * @return string
     */
    public static function staticMethod()
    {
        return "test";
    }

    public static function callStaticMethodBySelf()
    {
        return self::staticMethod();
    }

    public static function callStaticMethodByStatic()
    {
        return static::staticMethod();
    }
}
