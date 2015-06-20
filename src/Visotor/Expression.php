<?php

/**
 * Created by PhpStorm.
 * User: ovr
 * Date: 20.06.15
 * Time: 16:39
 */

namespace PHPSA\Visotor;

use PHPSA\Context;

class Expression
{
    /**
     * @var Context
     */
    protected $context;

    protected function passMethodCall(\PhpParser\Node\Expr\MethodCall $expr)
    {
        if ($expr->var instanceof \PhpParser\Node\Expr\Variable) {
            if ($expr->var->name == 'this') {
                if (!$this->context->scope->hasMethod($expr->name)) {
                    var_dump('fuck');
                }
            }
        }
    }

    public function __construct($expr, $context)
    {
        $this->context = $context;

        switch (get_class($expr)) {
            case 'PhpParser\Node\Expr\MethodCall';
                $this->passMethodCall($expr);
                break;
        }
    }
}