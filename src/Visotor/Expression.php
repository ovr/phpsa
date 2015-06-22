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
        if ($expr->class instanceof Node\Name) {
            $scope = $expr->class->parts[0];
            $name = $expr->name;

            $error = false;

            if ($scope == 'self') {
                if (!$this->context->scope->hasMethod($name)) {
                    $error = true;
                } else {
                    $method = $this->context->scope->getMethod($name);
                    if (!$method->isStatic()) {
                        $error = true;
                    }
                }
            }

            if ($error) {
                $this->context->notice(
                    'undefined-scall',
                    sprintf('Static method %s() is not exists on %s scope', $name, $scope),
                    $expr
                );
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

    protected function passConstFetch(Node\Expr\ClassConstFetch $expr)
    {
        if ($expr->class instanceof Node\Name) {
            $scope = $expr->class->parts[0];
            if ($scope == 'self') {
                if (!$this->context->scope->hasConst($expr->name)) {
                    $this->context->notice(
                        'undefined-const',
                        sprintf('Constant %s is not exists on %s scope', $expr->name, $scope),
                        $expr
                    );
                }
            }
        }
    }

    public function passSymbol(Node\Expr\Assign $expr)
    {
        $name = $expr->var->name;

        $symbol = $this->context->getSymbol($name);
        if ($symbol) {
            $symbol->incSets();
        } else {
            if ($expr->expr instanceof Node\Scalar\LNumber) {
                return $this->context->addSymbol($name, $expr->expr->value);
            }

            $this->context->addSymbol($name);
        }

    }

    public function passBinaryOpDiv(Node\Expr\BinaryOp\Div $expr)
    {
        if ($expr->right instanceof Node\Scalar\LNumber) {
            if ($expr->right->value == 0) {
                $this->context->notice(
                    'division-zero',
                    sprintf('You trying to use division on %s', $expr->right->value),
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
            case 'PhpParser\Node\Expr\ClassConstFetch';
                $this->passConstFetch($expr);
                break;
            case 'PhpParser\Node\Expr\Assign';
                $this->passSymbol($expr);
                break;
            case 'PhpParser\Node\Expr\BinaryOp\Div';
                $this->passBinaryOpDiv($expr);
                break;
            default:
                var_dump(get_class($expr));
//                var_dump($expr);
                break;
        }
    }
}