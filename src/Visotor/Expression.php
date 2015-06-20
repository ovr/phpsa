<?php

/**
 * Created by PhpStorm.
 * User: ovr
 * Date: 20.06.15
 * Time: 16:39
 */

namespace PHPSA\Visotor;

use PHPSA\Context;
use PhpParser\Node;

class Expression
{
    /**
     * @var Context
     */
    protected $context;

    protected function passMethodCall(Node\Expr\MethodCall $expr)
    {
        if ($expr->var instanceof Node\Expr\Variable) {
            if ($expr->var->name == 'this') {
                if (!$this->context->scope->hasMethod($expr->name)) {
                    
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