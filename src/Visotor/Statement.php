<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
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