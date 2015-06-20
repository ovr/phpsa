<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
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
                    $this->context->notice(
                        'undefined-mcall',
                        sprintf('Method %s() is not exists on %s scope.', $expr->name, $expr->var->name),
                        $expr
                    );
                }
            }
        }
    }

    protected function passPropertyFetch(Node\Expr\PropertyFetch $expr)
    {
        if (!isset($expr->var)) {
            var_dump($expr);
            die();
        }

        if ($expr->var->name == 'this') {
            $this->context->notice(
                'undefined-property',
                sprintf('Property %s is not exists on %s scope.', $expr->name, $expr->var->name),
                $expr
            );
        }
    }

    public function __construct($expr, $context)
    {
        $this->context = $context;

        switch (get_class($expr)) {
            case 'PhpParser\Node\Expr\MethodCall';
                $this->passMethodCall($expr);
                break;
            case 'PhpParser\Node\Expr\PropertyFetch';
                $this->passPropertyFetch($expr);
                break;
            default:
                var_dump(get_class($expr));
//                var_dump($expr);
                break;
        }
    }
}