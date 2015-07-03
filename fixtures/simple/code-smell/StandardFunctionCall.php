<?php

namespace Tests\Simple\CodeSmell;

/**
 * Class Standard
 * @package Tests\Simple\CodeSmell
 */
class StandardFunctionCall
{
    /**
     * @return string
     */
    public function testJsonEncode()
    {
        return (string) json_encode(array(
            'price' => 12345
        ));
    }
}
