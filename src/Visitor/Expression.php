<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor;

use PHPSA\Context;
use PhpParser\Node;
use PHPSA\Variable;

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

                return true;
            }
        }

        var_dump('Unknown method call');
        return false;
    }

    protected function passFunctionCall(Node\Expr\FuncCall $expr)
    {
        if (!function_exists($expr->name->parts[0])) {
            $this->context->notice(
                'undefined-fcall',
                sprintf('Function %s() is not exists', $expr->name->parts[0]),
                $expr
            );

            return false;
        }

        return true;
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

                return false;
            }

            return true;
        }

        var_dump('Unknown static function call');
        return false;
    }

    protected function passPropertyFetch(Node\Expr\PropertyFetch $expr)
    {
        if ($expr->var->name == 'this') {
            if (!$this->context->scope->hasProperty($expr->name)) {
                return $this->context->notice(
                    'undefined-property',
                    sprintf('Property %s is not exists on %s scope', $expr->name, $expr->var->name),
                    $expr
                );
            }

            return true;
        }

        var_dump('Unknown property fetch');
        return false;
    }

    protected function passConstFetch(Node\Expr\ClassConstFetch $expr)
    {
        if ($expr->class instanceof Node\Name) {
            $scope = $expr->class->parts[0];
            if ($scope == 'self') {
                if (!$this->context->scope->hasConst($expr->name)) {
                    return $this->context->notice(
                        'undefined-const',
                        sprintf('Constant %s is not exists on %s scope', $expr->name, $scope),
                        $expr
                    );
                }

                return true;
            }
        }

        var_dump('Unknown const fetch');
        return false;
    }

    public function passSymbol(Node\Expr\Assign $expr)
    {
        $name = $expr->var->name;

        $symbol = $this->context->getSymbol($name);
        if ($symbol) {
            return $symbol->incSets();
        }

//        if ($expr->expr instanceof Node\Scalar\LNumber) {
//            return $this->context->addVariable(new Variable($name, $expr->expr->value));
//        } else {
//            var_dump($expr->expr);
//            die();
//        }


        $compiledExpression = new Expression($expr->expr, $this->context);
        $result = $compiledExpression->compile($expr->expr);

        $this->context->addSymbol($name);

        return true;
    }

    public function passExprVariable(Node\Expr\Variable $expr)
    {
        $variable = $this->context->getSymbol($expr->name);
        if ($variable) {
            return $variable->incGets();
        }

        $this->context->notice(
            'undefined-variable',
            sprintf('You trying to use undefined variable $%s', $expr->name),
            $expr
        );
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

        $left = (new Expression($expr->left, $this->context))->compile($expr->left);
        $right = (new Expression($expr->right, $this->context))->compile($expr->right);

        return true;
    }

    public function passBinaryOpPlus(Node\Expr\BinaryOp\Plus $expr)
    {
        $left = (new Expression($expr->left, $this->context))->compile($expr->left);
        $right = (new Expression($expr->right, $this->context))->compile($expr->right);

        return true;
    }

    public function getLNumber(Node\Scalar\LNumber $scalar)
    {
        return $scalar->value;
    }

    public function getDNumber(Node\Scalar\DNumber $scalar)
    {
        return $scalar->value;
    }

    public function getString(Node\Scalar\String_ $scalar)
    {
        return $scalar->value;
    }

    public function constFetch(Node\Expr\ConstFetch $expr)
    {
        if ($expr->name instanceof Node\Name) {
            if ($expr->name->parts[0] === "true") {
                return true;
            }

            if ($expr->name->parts[0] === "false") {
                return false;
            }
        }

        var_dump('Unknown const fetch');
    }

    public function __construct($expr, $context)
    {
        $this->context = $context;
    }

    public function compile($expr)
    {
        switch (get_class($expr)) {
            case 'PhpParser\Node\Expr\MethodCall';
                return $this->passMethodCall($expr);
            case 'PhpParser\Node\Expr\PropertyFetch';
                return $this->passPropertyFetch($expr);
            case 'PhpParser\Node\Expr\FuncCall';
                return $this->passFunctionCall($expr);
            case 'PhpParser\Node\Expr\StaticCall';
                return $this->passStaticFunctionCall($expr);
            case 'PhpParser\Node\Expr\ClassConstFetch';
                return $this->passConstFetch($expr);
            case 'PhpParser\Node\Expr\Assign';
                return $this->passSymbol($expr);
            case 'PhpParser\Node\Expr\Variable';
                return $this->passExprVariable($expr);
            case 'PhpParser\Node\Expr\BinaryOp\Div';
                return $this->passBinaryOpDiv($expr);
            case 'PhpParser\Node\Expr\BinaryOp\Plus';
                return $this->passBinaryOpPlus($expr);
            case 'PhpParser\Node\Scalar\LNumber';
                return $this->getLNumber($expr);
            case 'PhpParser\Node\Scalar\DNumber';
                return $this->getDNumber($expr);
            case 'PhpParser\Node\Scalar\String_';
                return $this->getString($expr);
            case 'PhpParser\Node\Expr\ConstFetch';
                return $this->constFetch($expr);
            default:
                var_dump(get_class($expr));
                return -1;
                break;
        }
    }
}