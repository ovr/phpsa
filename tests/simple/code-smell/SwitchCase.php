<?php

namespace Tests\Simple\CodeSmell;

/**
 * Class Cast
 * @package Tests\Simple\CodeSmell
 */
class SwitchCase
{
    const A = 1;

    const B = 1;

    const C = 1;

    /**
     * @param $a
     */
    public function testDuplicationInSwitch($a)
    {
        switch ($a) {
            case 1:
            //Wrong....
            case 1:
            case 2:
                break;
            case 3:
                break;
            default:

                break;
        }
    }

    /**
     * @param $a
     * @return bool
     */
    public function testDuplicationInSwitchWithConst($a)
    {
        switch ($a) {
            case self::A:
                //Wrong....
            case self::A:
            case self::B:
                return true;
                break;
            case self::C:
                return false;
                break;
            default:

                break;
        }
    }
}
