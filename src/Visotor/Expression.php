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
                        sprintf('Method %s() is not exists on %s scope', $expr->name, $expr->var->name),
                        $expr
                    );
                }
            }
        }
    }

    protected function passFunctionCall(Node\Expr\FuncCall $expr)
    {
        if (!function_exists($expr->name->parts[0])) {
            $this->context->notice(
                'undefined-fcall',
                sprintf('Function %s() is not exists', $expr->name->parts[0]),
                $expr
            );
        }
    }

    protected function passStaticFunctionCall(Node\Expr\StaticCall $expr)
    {
        if ($expr->var instanceof Node\Expr\Variable) {
            if ($expr->var->name == 'self') {
                if (!$this->context->scope->hasMethod($expr->name)) {
                    $this->context->notice(
                        'undefined-scall',
                        sprintf('Method %s() is not exists on %s scope', $expr->name, $expr->var->name),
                        $expr
                    );
                }
            }
        }
    }

    protected function passPropertyFetch(Node\Expr\PropertyFetch $expr)
    {
        if ($expr->var->name == 'this') {
            if (!$this->context->scope->hasProperty($expr->name)) {
                $this->context->notice(
                    'undefined-property',
                    sprintf('Property %s is not exists on %s scope', $expr->name, $expr->var->name),
                    $expr
                );
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
            case 'PhpParser\Node\Expr\PropertyFetch';
                $this->passPropertyFetch($expr);
                break;
            case 'PhpParser\Node\Expr\FuncCall';
                $this->passFunctionCall($expr);
                break;
            case 'PhpParser\Node\Expr\StaticCall';
                $this->passStaticFunctionCall($expr);
                break;
            default:
                var_dump(get_class($expr));
//                var_dump($expr);
                break;
        }
    }
}