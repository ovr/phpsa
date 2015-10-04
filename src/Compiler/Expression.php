<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PhpParser\Node;
use PHPSA\Variable;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use RuntimeException;

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
     * @return ExpressionCompilerInterface|AbstractExpressionCompiler
     */
    protected function factory($expr)
    {
        switch (get_class($expr)) {
            /**
             * Call(s)
             */
            case 'PhpParser\Node\Expr\MethodCall':
                return new Expression\MethodCall();
            case 'PhpParser\Node\Expr\FuncCall':
                return new Expression\FunctionCall();
            case 'PhpParser\Node\Expr\StaticCall':
                return new Expression\StaticCall();
            /**
             * Operators
             */
            case 'PhpParser\Node\Expr\New_':
                return new Expression\Operators\NewOp();
            case 'PhpParser\Node\Expr\BinaryOp\Identical':
                return new Expression\BinaryOp\Identical();
            case 'PhpParser\Node\Expr\BinaryOp\Equal':
                return new Expression\BinaryOp\Equal();
            /**
             * @link http://php.net/manual/en/language.operators.increment.php
             */
            case 'PhpParser\Node\Expr\PostInc':
                return new Expression\Operators\PostInc();
            case 'PhpParser\Node\Expr\PostDec':
                return new Expression\Operators\PostDec();
            /**
             * Arithmetical
             */
            case 'PhpParser\Node\Expr\BinaryOp\Div':
                return new Expression\Operators\Arithmetical\Div();
            case 'PhpParser\Node\Expr\BinaryOp\Plus':
                return new Expression\Operators\Arithmetical\Plus();
            case 'PhpParser\Node\Expr\BinaryOp\Minus':
                return new Expression\Operators\Arithmetical\Minus();
            case 'PhpParser\Node\Expr\BinaryOp\Mul':
                return new Expression\Operators\Arithmetical\Mul();
            case 'PhpParser\Node\Expr\BinaryOp\Mod':
                return new Expression\Operators\Arithmetical\Mod();
            /**
             * Bitwise
             * @link http://php.net/manual/ru/language.operators.bitwise.php
             */
            case 'PhpParser\Node\Expr\BinaryOp\BitwiseOr':
                return new Expression\Operators\Bitwise\BitwiseOr();
            case 'PhpParser\Node\Expr\BinaryOp\BitwiseXor':
                return new Expression\Operators\Bitwise\BitwiseXor();
            case 'PhpParser\Node\Expr\BinaryOp\BitwiseAnd':
                return new Expression\Operators\Bitwise\BitwiseAnd();
            case 'PhpParser\Node\Expr\BinaryOp\ShiftRight':
                return new Expression\Operators\Bitwise\ShiftRight();
            case 'PhpParser\Node\Expr\BinaryOp\ShiftLeft':
                return new Expression\Operators\Bitwise\ShiftLeft();
            case 'PhpParser\Node\Expr\BitwiseNot':
                return new Expression\Operators\Bitwise\BitwiseNot();
            /**
             * Logical
             */
            case 'PhpParser\Node\Expr\BinaryOp\BooleanOr':
                return new Expression\Operators\Logical\BooleanOr();
            case 'PhpParser\Node\Expr\BinaryOp\BooleanAnd':
                return new Expression\Operators\Logical\BooleanAnd();
            case 'PhpParser\Node\Expr\BooleanNot':
                return new Expression\Operators\Logical\BooleanNot();
            /**
             * Comparison
             */
            case 'PhpParser\Node\Expr\BinaryOp\Greater':
                return new Expression\Operators\Comparison\Greater();
            case 'PhpParser\Node\Expr\BinaryOp\GreaterOrEqual':
                return new Expression\Operators\Comparison\GreaterOrEqual();
            case 'PhpParser\Node\Expr\BinaryOp\Smaller':
                return new Expression\Operators\Comparison\Smaller();
            case 'PhpParser\Node\Expr\BinaryOp\SmallerOrEqual':
                return new Expression\Operators\Comparison\SmallerOrEqual();
            /**
             * Another
             */
            case 'PhpParser\Node\Expr\UnaryMinus':
                return new Expression\Operators\UnaryMinus();
            case 'PhpParser\Node\Expr\UnaryPlus':
                return new Expression\Operators\UnaryPlus();
        }

        return false;
    }

    /**
     * @param $expr
     * @return CompiledExpression
     */
    public function compile($expr)
    {
        $className = get_class($expr);
        switch ($className) {
            case 'PhpParser\Node\Expr\PropertyFetch':
                return $this->passPropertyFetch($expr);
            case 'PhpParser\Node\Expr\ClassConstFetch':
                return $this->passConstFetch($expr);
            case 'PhpParser\Node\Expr\Assign':
                return $this->passSymbol($expr);
            case 'PhpParser\Node\Expr\AssignRef':
                return $this->passSymbolByRef($expr);
            case 'PhpParser\Node\Expr\Variable':
                return $this->passExprVariable($expr);
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
             * Expressions
             */
            case 'PhpParser\Node\Expr\Array_':
                return $this->getArray($expr);
            case 'PhpParser\Node\Expr\ConstFetch':
                return $this->constFetch($expr);
            case 'PhpParser\Node\Name':
                return $this->getNodeName($expr);
            /**
             * Simple Scalar(s)
             */
            case 'PHPSA\Node\Scalar\Nil':
                return new CompiledExpression(CompiledExpression::NULL);
            case 'PhpParser\Node\Scalar\LNumber':
                return new CompiledExpression(CompiledExpression::LNUMBER, $expr->value);
            case 'PhpParser\Node\Scalar\DNumber':
                return new CompiledExpression(CompiledExpression::DNUMBER, $expr->value);
            case 'PhpParser\Node\Scalar\String_':
                return new CompiledExpression(CompiledExpression::STRING, $expr->value);
            case 'PHPSA\Node\Scalar\Boolean':
                return new CompiledExpression(CompiledExpression::BOOLEAN, $expr->value);
            case 'PHPSA\Node\Scalar\Fake':
                return new CompiledExpression($expr->type, $expr->value);
        }

        $expressionCompiler = $this->factory($expr);
        if (!$expressionCompiler) {
            $this->context->debug("Expression compiler is not implemented for {$className}");
            return new CompiledExpression(CompiledExpression::UNIMPLEMENTED);
        }

        $result = $expressionCompiler->pass($expr, $this->context);
        if (!$result instanceof CompiledExpression) {
            throw new RuntimeException('Please return CompiledExpression from ' . get_class($expressionCompiler));
        }

        return $result;
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
        }

        return new CompiledExpression(CompiledExpression::NULL, null);
    }

    /**
     * @param Node\Expr\PropertyFetch $expr
     * @return CompiledExpression
     */
    protected function passPropertyFetch(Node\Expr\PropertyFetch $expr)
    {
        $scopeExpression = $this->compile($expr->var);
        switch ($scopeExpression->getType()) {
            case CompiledExpression::OBJECT:
                if ($scopeExpression->getValue() == 'this') {
                    if ($this->context->scope === null) {
                        throw new RuntimeException('Current $this scope is null');
                    }

                    $propertyName = is_string($expr->name) ? $expr->name : false;
                    if ($expr->name instanceof Variable) {
                        /**
                         * @todo implement fetch from symbol table
                         */
                        //$methodName = $expr->name->name;
                    }

                    if ($propertyName) {
                        if (!$this->context->scope->hasProperty($propertyName)) {
                            $this->context->notice(
                                'undefined-property',
                                sprintf('Property %s does not exist in %s scope', $propertyName, $scopeExpression->getValue()),
                                $expr
                            );
                        }
                    }
                }
                break;
            default:
                $this->context->debug('You cannot fetch property from not object type');
                break;
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

            if ($scope == 'self' || $scope == 'this') {
                if ($this->context->scope === null) {
                    throw new RuntimeException("Current {$scope} scope is null");
                }

                if (!$this->context->scope->hasConst($expr->name)) {
                    $this->context->notice(
                        'undefined-const',
                        sprintf('Constant %s does not exist in %s scope', $expr->name, $scope),
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
        $expression = new Expression($this->context);
        $compiledExpression = $expression->compile($expr->expr);

        if ($expr->var instanceof Node\Expr\List_) {
            $isCorrectType = false;

            switch ($compiledExpression->getType()) {
                case CompiledExpression::ARR:
                    $isCorrectType = true;
                    break;
            }

            if ($expr->var->vars) {
                foreach ($expr->var->vars as $key => $var) {
                    if ($var instanceof Node\Expr\Variable) {
                        $name = $expr->var->name;

                        $symbol = $this->context->getSymbol($name);
                        if (!$symbol) {
                            $symbol = new Variable($name, null, CompiledExpression::UNKNOWN);
                            $this->context->addVariable($symbol);
                        }

                        if (!$isCorrectType) {
                            $symbol->modify(CompiledExpression::NULL, null);
                        }

                        $symbol->incSets();
                    }
                }
            }

            return new CompiledExpression();
        }

        if ($expr->var instanceof Node\Expr\Variable) {
            $name = $expr->var->name;

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
     * @param Node\Expr\AssignRef $expr
     * @return CompiledExpression
     */
    protected function passSymbolByRef(Node\Expr\AssignRef $expr)
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

                if ($expr->expr instanceof Node\Expr\Variable) {
                    $rightVarName = $expr->expr->name;

                    $rightSymbol = $this->context->getSymbol($rightVarName);
                    if ($rightSymbol) {
                        $rightSymbol->incUse();
                        $symbol->setReferencedTo($rightSymbol);
                    } else {
                        $this->context->debug('Cannot fetch variable by name: ' . $rightVarName);
                    }
                }

                $this->context->debug('Unknown how to pass referenced to symbol: ' . get_class($expr->expr));
            } else {
                $symbol = new Variable($name, $compiledExpression->getValue(), $compiledExpression->getType(), true);
                $this->context->addVariable($symbol);
            }

            $symbol->incSets();
            return $compiledExpression;
        }

        $this->context->debug('Unknown how to pass symbol by ref');
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

        $resultArray = array();

        foreach ($expr->items as $item) {
            $compiledValueResult = $this->compile($item->value);
            if ($item->key) {
                $compiledKeyResult = $this->compile($item->key);
                switch ($compiledKeyResult->getType()) {
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                    case CompiledExpression::BOOLEAN:
                    case CompiledExpression::NULL:
                    case CompiledExpression::STRING:
                        $resultArray[$compiledKeyResult->getValue()] = $compiledValueResult->getValue();
                        break;
                    default:
                        $this->context->debug("Type {$compiledKeyResult->getType()} is not supported for key value");
                        return new CompiledExpression(CompiledExpression::ARR);
                        break;
                }
            } else {
                $resultArray[] = $compiledValueResult->getValue();
            }
        }

        return new CompiledExpression(CompiledExpression::ARR, $resultArray);
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
            if ($expr->name->parts[0] === 'true') {
                return new CompiledExpression(CompiledExpression::BOOLEAN, true);
            }

            if ($expr->name->parts[0] === 'false') {
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
