<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PhpParser\Node;
use PHPSA\Node\Scalar\Boolean;
use PHPSA\Node\Scalar\Nil;
use PHPSA\Variable;

class Expression
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
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
            case 'PhpParser\Node\Expr\MethodCall':
                return $this->passMethodCall($expr);
            case 'PhpParser\Node\Expr\PropertyFetch':
                return $this->passPropertyFetch($expr);
            case 'PhpParser\Node\Expr\FuncCall':
                return $this->passFunctionCall($expr);
            case 'PhpParser\Node\Expr\StaticCall':
                return $this->passStaticFunctionCall($expr);
            case 'PhpParser\Node\Expr\ClassConstFetch':
                return $this->passConstFetch($expr);
            case 'PhpParser\Node\Expr\Assign':
                return $this->passSymbol($expr);
            case 'PhpParser\Node\Expr\Variable':
                return $this->passExprVariable($expr);
            /**
             * Operators
             */
            case 'PhpParser\Node\Expr\BinaryOp\Identical':
                return $this->passBinaryOpIdentical($expr);
            case 'PhpParser\Node\Expr\BinaryOp\Equal':
                return $this->passBinaryOpEqual($expr);
            case 'PhpParser\Node\Expr\BinaryOp\Div':
                return $this->passBinaryOpDiv($expr);
            case 'PhpParser\Node\Expr\BinaryOp\Plus':
                return $this->passBinaryOpPlus($expr);
            case 'PhpParser\Node\Expr\BinaryOp\BitwiseXor':
                return $this->passBinaryOpXor($expr);
            case 'PhpParser\Node\Expr\BinaryOp\Mul':
                return $this->passBinaryOpMul($expr);
            case 'PhpParser\Node\Expr\BinaryOp\Minus':
                return $this->passBinaryOpMinus($expr);
            case 'PhpParser\Node\Expr\BinaryOp\BooleanOr':
                return $this->passBinaryOpBooleanOr($expr);
            /**
             * Another
             */
            case 'PhpParser\Node\Expr\BooleanNot':
                return $this->passBooleanNot($expr);
            case 'PhpParser\Node\Expr\UnaryMinus':
                return $this->passUnaryMinus($expr);
            case 'PhpParser\Node\Expr\New_':
                return $this->passNew($expr);
            /**
             * Cast operators
             */
            case 'PhpParser\Node\Expr\Cast\Bool_':
                return $this->passCastBoolean($expr);
            case 'PhpParser\Node\Expr\Cast\Int_':
                return $this->passCastInt($expr);
            case 'PhpParser\Node\Expr\Cast\Double':
                return $this->passCastFloat($expr);
            case 'PhpParser\Node\Expr\Cast\String_':
                return $this->passCastString($expr);
            case 'PhpParser\Node\Expr\Cast\Unset_':
                return $this->passCastUnset($expr);
            /**
             * Scalars
             */
            case 'PHPSA\Node\Scalar\Nil':
                return $this->getNull();
            case 'PhpParser\Node\Scalar\LNumber':
                return $this->getLNumber($expr);
            case 'PhpParser\Node\Scalar\DNumber':
                return $this->getDNumber($expr);
            case 'PhpParser\Node\Scalar\String_':
                return $this->getString($expr);
            case 'PhpParser\Node\Expr\Array_':
                return $this->getArray($expr);
            case 'PHPSA\Node\Scalar\Boolean':
                return $this->getBoolean($expr);
            case 'PhpParser\Node\Expr\ConstFetch':
                return $this->constFetch($expr);
            case 'PhpParser\Node\Name':
                return $this->getNodeName($expr);
        }

        $this->context->debug('Unknown expression: ' . get_class($expr));
        return new CompiledExpression(CompiledExpression::UNIMPLEMENTED);
    }

    /**
     * !{expr}
     *
     * @param Node\Expr\BooleanNot $expr
     * @return CompiledExpression
     */
    protected function passBooleanNot(Node\Expr\BooleanNot $expr)
    {
        $compiledExpression = $this->compile($expr->expr);
        switch ($compiledExpression->getType()) {
            case CompiledExpression::DNUMBER:
            case CompiledExpression::LNUMBER:
            case CompiledExpression::STRING:
            case CompiledExpression::BOOLEAN:
                return new CompiledExpression($compiledExpression->getType(), !$compiledExpression->getValue());
        }


        return new CompiledExpression();
    }

    /**
     * @param Node\Name $expr
     * @return CompiledExpression
     */
    public function getNodeName(Node\Name $expr)
    {
        if ($expr->parts[0] === 'null') {
            return new CompiledExpression(CompiledExpression::NULL);
        }

        $this->context->debug('Unknown how to get node name');
        return new CompiledExpression();
    }

    protected function passNew(Node\Expr\New_ $expr)
    {
        if ($expr->class instanceof Node\Name) {
            return new CompiledExpression(CompiledExpression::OBJECT, $expr->class->parts[0]);
        }

        $this->context->debug('Unknown how to pass new');
        return new CompiledExpression();
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

                return new CompiledExpression();
            }
        }

        $expression = new Expression($this->context);
        $expression->compile($expr->var);

        $this->context->debug('Unknown method call');
        return new CompiledExpression();
    }

    /**
     * (bool) {$expr}
     *
     * @param Node\Expr\Cast\Bool_ $expr
     * @return CompiledExpression
     */
    protected function passCastBoolean(Node\Expr\Cast\Bool_ $expr)
    {
        $expression = new Expression($this->context);
        $compiledExpression = $expression->compile($expr->expr);

        switch ($compiledExpression->getType()) {
            case CompiledExpression::BOOLEAN:
                $this->context->notice('stupid-cast', "You are trying to cast 'boolean' to 'boolean'", $expr);
                return $compiledExpression;
            case CompiledExpression::DNUMBER:
            case CompiledExpression::LNUMBER:
                return new CompiledExpression(CompiledExpression::BOOLEAN, (bool) $compiledExpression->getValue());
        }

        return new CompiledExpression();
    }

    /**
     * (int) {$expr}
     *
     * @param Node\Expr\Cast\Int_ $expr
     * @return CompiledExpression
     */
    protected function passCastInt(Node\Expr\Cast\Int_ $expr)
    {
        $expression = new Expression($this->context);
        $compiledExpression = $expression->compile($expr->expr);

        switch ($compiledExpression->getType()) {
            case CompiledExpression::LNUMBER:
                $this->context->notice('stupid-cast', "You are trying to cast 'int' to 'int'", $expr);
                return $compiledExpression;
            case CompiledExpression::BOOLEAN:
            case CompiledExpression::DNUMBER:
            case CompiledExpression::STRING:
                return new CompiledExpression(CompiledExpression::LNUMBER, (int) $compiledExpression->getValue());
        }

        return new CompiledExpression();
    }

    /**
     * (float) {$expr}
     *
     * @param Node\Expr\Cast\Double $expr
     * @return CompiledExpression
     */
    protected function passCastFloat(Node\Expr\Cast\Double $expr)
    {
        $expression = new Expression($this->context);
        $compiledExpression = $expression->compile($expr->expr);

        switch ($compiledExpression->getType()) {
            case CompiledExpression::DNUMBER:
                $this->context->notice('stupid-cast', "You are trying to cast 'float' to 'float'", $expr);
                return $compiledExpression;
            case CompiledExpression::BOOLEAN:
            case CompiledExpression::LNUMBER:
            case CompiledExpression::STRING:
                return new CompiledExpression(CompiledExpression::DNUMBER, (float) $compiledExpression->getValue());
        }

        return new CompiledExpression();
    }

    /**
     * (string) {$expr}
     *
     * @param Node\Expr\Cast\String_ $expr
     * @return CompiledExpression
     */
    protected function passCastString(Node\Expr\Cast\String_ $expr)
    {
        $expression = new Expression($this->context);
        $compiledExpression = $expression->compile($expr->expr);

        switch ($compiledExpression->getType()) {
            case CompiledExpression::STRING:
                $this->context->notice('stupid-cast', "You are trying to cast 'string' to 'string'", $expr);
                return $compiledExpression;
            case CompiledExpression::BOOLEAN:
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
                return new CompiledExpression(CompiledExpression::DNUMBER, (string) $compiledExpression->getValue());
        }

        return new CompiledExpression();
    }

    /**
     * (unset) {$expr}
     *
     * @param Node\Expr\Cast\Unset_ $expr
     * @return CompiledExpression
     */
    protected function passCastUnset(Node\Expr\Cast\Unset_ $expr)
    {
        $expression = new Expression($this->context);
        $compiledExpression = $expression->compile($expr->expr);

        switch ($compiledExpression->getType()) {
            case CompiledExpression::NULL:
                $this->context->notice('stupid-cast', "You are trying to cast 'unset' to 'null'", $expr);
                return $compiledExpression;
            case CompiledExpression::BOOLEAN:
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
                return new CompiledExpression(CompiledExpression::DNUMBER, (unset) $compiledExpression->getValue());
        }

        return new CompiledExpression(CompiledExpression::NULL, null);
    }

    /**
     * {expr}();
     *
     * @param Node\Expr\FuncCall $expr
     * @return CompiledExpression
     */
    protected function passFunctionCall(Node\Expr\FuncCall $expr)
    {
        if (!function_exists($expr->name->parts[0])) {
            $this->context->notice(
                'undefined-fcall',
                sprintf('Function %s() is not exists', $expr->name->parts[0]),
                $expr
            );

            return new CompiledExpression();
        }

        $reflector = new \Ovr\PHPReflection\Reflector(\Ovr\PHPReflection\Reflector::manuallyFactory());
        $functionReflection = $reflector->getFunction($expr->name->parts[0]);
        if ($functionReflection) {
            return new CompiledExpression($functionReflection->returnType, null);
        }

        return new CompiledExpression();
    }

    /**
     * {expr}::{expr}();
     *
     * @param Node\Expr\StaticCall $expr
     * @return CompiledExpression
     */
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

                return new CompiledExpression();
            }

            return new CompiledExpression();
        }

        $this->context->debug('Unknown static function call');
        return new CompiledExpression();
    }

    /**
     * @param Node\Expr\PropertyFetch $expr
     * @return CompiledExpression
     */
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

        $this->context->debug('Unknown property fetch');
        return new CompiledExpression();
    }

    /**
     * @param Node\Expr\ClassConstFetch $expr
     * @return CompiledExpression
     */
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

                return new CompiledExpression();
            }
        }

        $this->context->debug('Unknown const fetch');
        return new CompiledExpression();
    }

    /**
     * @param Node\Expr\Assign $expr
     * @return CompiledExpression
     */
    protected function passSymbol(Node\Expr\Assign $expr)
    {
        if ($expr->var instanceof \PhpParser\Node\Expr\List_) {
            return new CompiledExpression();
        }

        if ($expr->var instanceof Node\Expr\Variable) {
            $name = $expr->var->name;

            $expression = new Expression($this->context);
            $compiledExpression = $expression->compile($expr->expr);

            $symbol = $this->context->getSymbol($name);
            if ($symbol) {
                $symbol->modify($compiledExpression->getType(), $compiledExpression->getValue());
            } else {
                $symbol = new Variable($name, $compiledExpression->getValue(), $compiledExpression->getType());
                $this->context->addVariable($symbol);
            }

            $symbol->incSets();
            return $compiledExpression;
        }

        $this->context->debug('Unknown how to pass symbol');
        return new CompiledExpression();
    }

    /**
     * @param Node\Expr\Variable $expr
     * @return CompiledExpression
     */
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

        return new CompiledExpression();
    }

    /**
     * {expr} / {expr}
     *
     * @param Node\Expr\BinaryOp\Div $expr
     * @return CompiledExpression
     */
    protected function passBinaryOpDiv(Node\Expr\BinaryOp\Div $expr)
    {
        $expression = new Expression($this->context);
        $left = $expression->compile($expr->left);

        $expression = new Expression($this->context);
        $right = $expression->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::DNUMBER:
                if ($left->isEquals(0)) {
                    $this->context->notice(
                        'division-zero',
                        sprintf('You trying to use division from %s/{expr}', $left->getValue()),
                        $expr
                    );

                    return new CompiledExpression(CompiledExpression::DNUMBER, 0.0);
                }
                break;
            case CompiledExpression::LNUMBER:
            case CompiledExpression::BOOLEAN:
                if ($left->isEquals(0)) {
                    $this->context->notice(
                        'division-zero',
                        sprintf('You trying to use division from %s/{expr}', $left->getValue()),
                        $expr
                    );

                    switch ($right->getType()) {
                        case CompiledExpression::LNUMBER:
                        case CompiledExpression::BOOLEAN:
                            return new CompiledExpression(CompiledExpression::LNUMBER, 0);
                        case CompiledExpression::DNUMBER:
                            return new CompiledExpression(CompiledExpression::DNUMBER, 0.0);
                    }
                }
                break;
        }

        switch ($right->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
            case CompiledExpression::BOOLEAN:
                if ($right->isEquals(0)) {
                    $this->context->notice(
                        'division-zero',
                        sprintf('You trying to use division on {expr}/%s', $right->getValue()),
                        $expr
                    );

                    return new CompiledExpression(CompiledExpression::UNKNOWN);
                }
        }

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
            case CompiledExpression::BOOLEAN:
                switch ($right->getType()) {
                    case CompiledExpression::BOOLEAN:
                        /**
                         * Boolean is true via isEquals(0) check is not passed before
                         * {int}/1 = {int}
                         * {double}/1 = {double}
                         */

                        $this->context->notice(
                            'division-on-true',
                            sprintf('You trying to use stupid division {expr}/true ~ {expr}/1 = {expr}', $right->getValue()),
                            $expr
                        );
                        //no break
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                    case CompiledExpression::BOOLEAN:
                        return CompiledExpression::fromZvalValue($left->getValue() / $right->getValue());
                }
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }

    /**
     * {expr} ^ {expr}
     *
     * @param Node\Expr\BinaryOp\BitwiseXor $expr
     * @return CompiledExpression
     */
    protected function passBinaryOpXor(Node\Expr\BinaryOp\BitwiseXor $expr)
    {
        $expression = new Expression($this->context);
        $left = $expression->compile($expr->left);

        $expression = new Expression($this->context);
        $right = $expression->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
            case CompiledExpression::BOOLEAN:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                    case CompiledExpression::BOOLEAN:
                        return new CompiledExpression(CompiledExpression::DNUMBER, $left->getValue() ^ $right->getValue());
                }
                break;
        }

        return new CompiledExpression();
    }

    /**
     * It's used in conditions
     * {left-expr} === {right-expr}
     *
     * @param Node\Expr\BinaryOp\Identical $expr
     * @return CompiledExpression
     */
    protected function passBinaryOpIdentical(Node\Expr\BinaryOp\Identical $expr)
    {
        $expression = new Expression($this->context);
        $left = $expression->compile($expr->left);

        $expression = new Expression($this->context);
        $right = $expression->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
            case CompiledExpression::BOOLEAN:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                    case CompiledExpression::BOOLEAN:
                        return new CompiledExpression(CompiledExpression::BOOLEAN, $left->getValue() === $right->getValue());
                }
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }

    /**
     * It's used in conditions
     * {left-expr} == {right-expr}
     *
     * @param Node\Expr\BinaryOp\Equal $expr
     * @return CompiledExpression
     */
    protected function passBinaryOpEqual(Node\Expr\BinaryOp\Equal $expr)
    {
        $expression = new Expression($this->context);
        $left = $expression->compile($expr->left);

        $expression = new Expression($this->context);
        $right = $expression->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
            case CompiledExpression::BOOLEAN:
            case CompiledExpression::ARR:
            case CompiledExpression::OBJECT:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                    case CompiledExpression::BOOLEAN:
                    case CompiledExpression::ARR:
                    case CompiledExpression::OBJECT:
                        return new CompiledExpression(CompiledExpression::BOOLEAN, $left->getValue() == $right->getValue());
                }
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }

    /**
     * -{expr}
     *
     * @param Node\Expr\UnaryMinus $expr
     * @return CompiledExpression
     */
    protected function passUnaryMinus(Node\Expr\UnaryMinus $expr)
    {
        $expression = new Expression($this->context);
        $left = $expression->compile($expr->expr);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
                return new CompiledExpression($left->getType(), -$left->getValue());
        }

        return new CompiledExpression();
    }

    /**
     * {expr} * {expr}
     *
     * @param Node\Expr\BinaryOp\Mul $expr
     * @return CompiledExpression
     */
    protected function passBinaryOpMul(Node\Expr\BinaryOp\Mul $expr)
    {
        $expression = new Expression($this->context);
        $left = $expression->compile($expr->left);

        $expression = new Expression($this->context);
        $right = $expression->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                        return new CompiledExpression(CompiledExpression::DNUMBER, $left->getValue() * $right->getValue());
                }
                break;
        }

        return new CompiledExpression();
    }

    /**
     * {expr} + {expr}
     *
     * @param Node\Expr\BinaryOp\Plus $expr
     * @return CompiledExpression
     */
    protected function passBinaryOpPlus(Node\Expr\BinaryOp\Plus $expr)
    {
        $expression = new Expression($this->context);
        $left = $expression->compile($expr->left);

        $expression = new Expression($this->context);
        $right = $expression->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                        /**
                         * php -r "var_dump(1 + 1);" int(2)
                         */
                        return new CompiledExpression(CompiledExpression::LNUMBER, $left->getValue() + $right->getValue());
                    case CompiledExpression::DNUMBER:
                        /**
                         * php -r "var_dump(1 + 1.0);" double(2)
                         */
                        return new CompiledExpression(CompiledExpression::DNUMBER, $left->getValue() + $right->getValue());
                }
                break;
            case CompiledExpression::DNUMBER:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                        /**
                         * php -r "var_dump(1.0 + 1);"   double(2)
                         * php -r "var_dump(1.0 + 1.0);" double(2)
                         */
                        return new CompiledExpression(CompiledExpression::DNUMBER, $left->getValue() + $right->getValue());
                }
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }

    /**
     * {expr} - {expr}
     *
     * @param Node\Expr\BinaryOp\Minus $expr
     * @return CompiledExpression
     */
    protected function passBinaryOpMinus(Node\Expr\BinaryOp\Minus $expr)
    {
        $expression = new Expression($this->context);
        $left = $expression->compile($expr->left);

        $expression = new Expression($this->context);
        $right = $expression->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                        return new CompiledExpression(CompiledExpression::LNUMBER, $left->getValue() - $right->getValue());
                }
                break;
            case CompiledExpression::DNUMBER:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                        return new CompiledExpression(CompiledExpression::DNUMBER, $left->getValue() - $right->getValue());
                }
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }

    /**
     * {expr} || {expr}
     *
     * @param Node\Expr\BinaryOp\BooleanOr $expr
     * @return CompiledExpression
     */
    protected function passBinaryOpBooleanOr(Node\Expr\BinaryOp\BooleanOr $expr)
    {
        $expression = new Expression($this->context);
        $left = $expression->compile($expr->left);

        $expression = new Expression($this->context);
        $right = $expression->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
            case CompiledExpression::STRING:
            case CompiledExpression::BOOLEAN:
            case CompiledExpression::NULL:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                    case CompiledExpression::STRING:
                    case CompiledExpression::BOOLEAN:
                    case CompiledExpression::NULL:
                        return CompiledExpression::fromZvalValue($left->getValue() || $right->getValue());
                }
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
     * Compile Array_ expression to CompiledExpression
     *
     * @param Node\Expr\Array_ $expr
     * @return CompiledExpression
     */
    protected function getArray(Node\Expr\Array_ $expr)
    {
        if ($expr->items === array()) {
            return new CompiledExpression(CompiledExpression::ARR, array());
        }

        return new CompiledExpression(CompiledExpression::ARR | CompiledExpression::UNKNOWN);
    }

    /**
     * Compile Boolean expression to CompiledExpression
     * Attention: Boolean is my node class not PHPParser ;)
     *
     * @param Boolean $scalar
     * @return CompiledExpression
     */
    protected function getBoolean(Boolean $scalar)
    {
        return new CompiledExpression(CompiledExpression::BOOLEAN, $scalar->value);
    }

    /**
     * Get new CompiledExpression with NULL type
     * Attention: Null is my node class not PHPParser ;)
     *
     * @return CompiledExpression
     */
    protected function getNull()
    {
        return new CompiledExpression(CompiledExpression::NULL);
    }

    /**
     * Convert const fetch expr to CompiledExpression
     *
     * @param Node\Expr\ConstFetch $expr
     * @return CompiledExpression
     */
    protected function constFetch(Node\Expr\ConstFetch $expr)
    {
        if ($expr->name instanceof Node\Name) {
            if ($expr->name->parts[0] === "true") {
                return new CompiledExpression(CompiledExpression::BOOLEAN, true);
            }

            if ($expr->name->parts[0] === "false") {
                return new CompiledExpression(CompiledExpression::BOOLEAN, false);
            }
        }

        /**
         * @todo Implement check
         */

        $expression = new Expression($this->context);
        $compiledExpr = $expression->compile($expr->name);

        return $compiledExpr;
    }
}
