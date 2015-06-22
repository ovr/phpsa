<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor;

class Statement
{
    protected function passReturn(\PhpParser\Node\Stmt\Return_ $st, $context)
    {
        $expr = (new Expression($st->expr, $context))->compile($st->expr);
    }

    public function __construct(\PhpParser\Node\Stmt $st, $context)
    {
        switch (get_class($st)) {
            case 'PhpParser\Node\Stmt\Return_':
                $this->passReturn($st, $context);
                break;
            default:
                var_dump(get_class($st));
//                var_dump($st);
                break;
        }
    }
}