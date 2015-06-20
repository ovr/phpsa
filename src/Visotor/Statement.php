<?php

/**
 * Created by PhpStorm.
 * User: ovr
 * Date: 20.06.15
 * Time: 16:39
 */

namespace PHPSA\Visotor;

class Statement
{
    protected function passReturn(\PhpParser\Node\Stmt\Return_ $st, $context)
    {
        $expr = new Expression($st->expr, $context);
    }


    public function __construct(\PhpParser\Node\Stmt $st, $context)
    {
        switch (get_class($st)) {
            case 'PhpParser\Node\Stmt\Return_':
                $this->passReturn($st, $context);
                break;
        }
    }
}