<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PhpParser\Node;
use PHPSA\Variable;

class Expression
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @param $expr
     * @param Context $context
     */
    public function __construct($expr, Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param $expr
     * @return CompiledExpression
     */
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
            case 'PhpParser\Node\Expr\BinaryOp\BitwiseXor';
                return $this->passBinaryOpXor($expr);
            case 'PhpParser\Node\Expr\BinaryOp\Mul';
                return $this->passBinaryOpMul($expr);
            case 'PhpParser\Node\Expr\BinaryOp\Minus';
                return $this->passBinaryOpMinus($expr);
            case 'PhpParser\Node\Expr\UnaryMinus';
                return $this->passUnaryMinus($expr);
            case 'PhpParser\Node\Scalar\LNumber';
                return $this->getLNumber($expr);
            case 'PhpParser\Node\Scalar\DNumber';
                return $this->getDNumber($expr);
            case 'PhpParser\Node\Scalar\String_';
                return $this->getString($expr);
            case 'PhpParser\Node\Expr\Cast\Bool_';
                return $this->passCastBoolean($expr);
            case 'PhpParser\Node\Expr\ConstFetch';
                return $this->constFetch($expr);
            default:
                var_dump('Unknown expression: ' . get_class($expr));
                return new CompiledExpression(-1);
                break;
        }
    }

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

        $expression = new Expression($expr->var, $this->context);
        $compiledExpression = $expression->compile($expr->var);

        var_dump('Unknown method call');
        return false;
    }

    /**
     * (bool) {$expr}
     *
     * @param Node\Expr\Cast\Bool_ $expr
     * @return CompiledExpression
     */
    protected function passCastBoolean(Node\Expr\Cast\Bool_ $expr)
    {
        $expression = new Expression($expr->expr, $this->context);
        $compiledExpression = $expression->compile($expr->expr);

        switch ($compiledExpression->getType()) {
            case CompiledExpression::BOOLEAN:
                //@todo CodeSmell
                return $compiledExpression;
                break;
            case CompiledExpression::DNUMBER:
            case CompiledExpression::LNUMBER:
                return new CompiledExpression(CompiledExpression::BOOLEAN, boolval($compiledExpression->getValue()));
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
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

    protected function passSymbol(Node\Expr\Assign $expr)
    {
        $name = $expr->var->name;

        $compiledExpression = new Expression($expr->expr, $this->context);
        $result = $compiledExpression->compile($expr->expr);

        $symbol = $this->context->getSymbol($name);
        if ($symbol) {
            $symbol->incSets();
            $symbol->modify($result->getType(), $result->getValue());
        } else {
            $this->context->addVariable(new Variable($name, $result->getValue(), $result->getType()));
        }

        return $compiledExpression;
    }

    protected function passExprVariable(Node\Expr\Variable $expr)
    {
        $variable = $this->context->getSymbol($expr->name);
        if ($variable) {
            $variable->incGets();

            return new CompiledExpression($variable->getType(), $variable->getName());
        }

        $this->context->notice(
            'undefined-variable',
            sprintf('You trying to use undefined variable $%s', $expr->name),
            $expr
        );

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }

    protected function passBinaryOpDiv(Node\Expr\BinaryOp\Div $expr)
    {
        $expression = new Expression($expr->left, $this->context);
        $left = $expression->compile($expr->left);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
            case CompiledExpression::UNKNOWN:
                if ($left->isEquals(0)) {
                    $this->context->notice(
                        'division-zero',
                        sprintf('You trying to use division from %s/{expr}', $left->getValue()),
                        $expr
                    );

                    /**
                     * Micro optimization -> 0/{expr} -> 0
                     */
                    return new CompiledExpression(CompiledExpression::LNUMBER, 0);
                }
                break;
            default:
                //
                break;
        }

        $expression = new Expression($expr->right, $this->context);
        $right = $expression->compile($expr->right);
        switch ($right->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
            case CompiledExpression::UNKNOWN:
                if ($right->isEquals(0)) {
                    $this->context->notice(
                        'division-zero',
                        sprintf('You trying to use division on {expr}/%s', $right->getValue()),
                        $expr
                    );

                    return new CompiledExpression(CompiledExpression::UNKNOWN);
                }
                break;
            default:
                //
                break;
        }

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                        return new CompiledExpression(CompiledExpression::DNUMBER, $left->getValue() / $right->getValue());
                        break;
                    default:
                        //
                        break;
                }
                break;
            default:
                //
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }

    protected function passBinaryOpXor(Node\Expr\BinaryOp\BitwiseXor $expr)
    {
        $expression = new Expression($expr->left, $this->context);
        $left = $expression->compile($expr->left);

        $expression = new Expression($expr->right, $this->context);
        $right = $expression->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                        return new CompiledExpression(CompiledExpression::DNUMBER, $left->getValue() ^ $right->getValue());
                        break;
                    default:
                        //@todo implement it
                        break;
                }
                break;
            default:
                //@todo implement it
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }

    protected function passUnaryMinus(Node\Expr\UnaryMinus $expr)
    {
        $expression = new Expression($expr->expr, $this->context);
        $left = $expression->compile($expr->expr);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
                return new CompiledExpression($left->getType(), -$left->getValue());
                break;
            default:
                //@todo implement it
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }

    protected function passBinaryOpMul(Node\Expr\BinaryOp\Mul $expr)
    {
        $expression = new Expression($expr->left, $this->context);
        $left = $expression->compile($expr->left);

        $expression = new Expression($expr->right, $this->context);
        $right = $expression->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                        return new CompiledExpression(CompiledExpression::DNUMBER, $left->getValue() * $right->getValue());
                        break;
                    default:
                        //@todo implement it
                        break;
                }
                break;
            default:
                //@todo implement it
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }

    protected function passBinaryOpPlus(Node\Expr\BinaryOp\Plus $expr)
    {
        $expression = new Expression($expr->left, $this->context);
        $left = $expression->compile($expr->left);

        $expression = new Expression($expr->right, $this->context);
        $right = $expression->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                        return new CompiledExpression(CompiledExpression::DNUMBER, $left->getValue() + $right->getValue());
                        break;
                    default:
                        //
                        break;
                }
                break;
            default:
                //
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }

    protected function passBinaryOpMinus(Node\Expr\BinaryOp\Minus $expr)
    {
        $expression = new Expression($expr->left, $this->context);
        $left = $expression->compile($expr->left);

        $expression = new Expression($expr->right, $this->context);
        $right = $expression->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                        return new CompiledExpression(CompiledExpression::DNUMBER, $left->getValue() - $right->getValue());
                        break;
                    default:
                        //
                        break;
                }
                break;
            default:
                //
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }

    /**
     * Convert lnumber scalar expr to CompiledExpression
     *
     * @param Node\Scalar\LNumber $scalar
     * @return CompiledExpression
     */
    protected function getLNumber(Node\Scalar\LNumber $scalar)
    {
        return new CompiledExpression(CompiledExpression::LNUMBER, $scalar->value);
    }

    /**
     * Convert dnumber expr to CompiledExpression
     *
     * @param Node\Scalar\DNumber $scalar
     * @return CompiledExpression
     */
    protected function getDNumber(Node\Scalar\DNumber $scalar)
    {
        return new CompiledExpression(CompiledExpression::DNUMBER, $scalar->value);
    }

    /**
     * Convert string scala expr to CompiledExpression
     *
     * @param Node\Scalar\String_ $scalar
     * @return CompiledExpression
     */
    protected function getString(Node\Scalar\String_ $scalar)
    {
        return new CompiledExpression(CompiledExpression::STRING, $scalar->value);
    }

    /**
     * Convert const fetch expr to CompiledExpression
     *
     * @param Node\Expr\ConstFetch $expr
     * @return bool|CompiledExpression
     */
    protected function constFetch(Node\Expr\ConstFetch $expr)
    {
        if ($expr->name instanceof Node\Name) {
            if ($expr->name->parts[0] === "true") {
                return new CompiledExpression(CompiledExpression::BOOLEAN, true);
            }

            if ($expr->name->parts[0] === "false") {
                return new CompiledExpression(CompiledExpression::LNUMBER, false);
            }
        }

        /**
         * @todo Implement check
         */

        $expression = new Expression($expr->name, $this->context);
        $compiledExpr = $expression->compile($expr->name);

        return $compiledExpr;
    }
}