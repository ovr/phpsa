<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler;

use InvalidArgumentException;
use phpDocumentor\Reflection\DocBlockFactory;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Event\ExpressionBeforeCompile;
use PHPSA\Context;
use PhpParser\Node;
use PHPSA\Definition\ClassDefinition;
use PHPSA\Exception\RuntimeException;
use PHPSA\Variable;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use Webiny\Component\EventManager\EventManager;

class Expression
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @param Context $context
     */
    public function __construct(Context $context, EventManager $eventManager)
    {
        $this->context = $context;
        $this->eventManager = $eventManager;
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
            case Node\Expr\MethodCall::class:
                return new Expression\MethodCall();
            case Node\Expr\FuncCall::class:
                return new Expression\FunctionCall();
            case Node\Expr\StaticCall::class:
                return new Expression\StaticCall();
            /**
             * Operators
             */
            case Node\Expr\New_::class:
                return new Expression\Operators\NewOp();
            case Node\Expr\Instanceof_::class:
                return new Expression\Operators\InstanceOfOp();
            /**
             * AssignOp
             */
            case Node\Expr\AssignOp\Pow::class:
                return new Expression\AssignOp\Pow();
            case Node\Expr\AssignOp\Plus::class:
                return new Expression\AssignOp\Plus();
            case Node\Expr\AssignOp\Minus::class:
                return new Expression\AssignOp\Minus();
            case Node\Expr\AssignOp\Mul::class:
                return new Expression\AssignOp\Mul();
            case Node\Expr\AssignOp\Div::class:
                return new Expression\AssignOp\Div();
            case Node\Expr\AssignOp\Mod::class:
                return new Expression\AssignOp\Mod();
            case Node\Expr\AssignOp\BitwiseOr::class:
                return new Expression\AssignOp\BitwiseOr();
            case Node\Expr\AssignOp\BitwiseAnd::class:
                return new Expression\AssignOp\BitwiseAnd();
            case Node\Expr\AssignOp\BitwiseXor::class:
                return new Expression\AssignOp\BitwiseXor();
            case Node\Expr\AssignOp\Concat::class:
                return new Expression\AssignOp\Concat();
            case Node\Expr\AssignOp\ShiftLeft::class:
                return new Expression\AssignOp\ShiftLeft();
            case Node\Expr\AssignOp\ShiftRight::class:
                return new Expression\AssignOp\ShiftRight();

            /**
             * BinaryOp
             */
            case Node\Expr\BinaryOp\Identical::class:
                return new Expression\BinaryOp\Identical();
            case Node\Expr\BinaryOp\Concat::class:
                return new Expression\Operators\Concat();
            case Node\Expr\BinaryOp\NotIdentical::class:
                return new Expression\BinaryOp\NotIdentical();
            case Node\Expr\BinaryOp\Equal::class:
                return new Expression\BinaryOp\Equal();
            case Node\Expr\BinaryOp\NotEqual::class:
                return new Expression\BinaryOp\NotEqual();
            case Node\Expr\BinaryOp\Spaceship::class:
                return new Expression\BinaryOp\SpaceShip();
            case Node\Expr\BinaryOp\Coalesce::class:
                return new Expression\BinaryOp\Coalesce();
                
            /**
             * @link http://php.net/manual/en/language.operators.increment.php
             */
            case Node\Expr\PostInc::class:
                return new Expression\Operators\PostInc();
            case Node\Expr\PostDec::class:
                return new Expression\Operators\PostDec();
            case Node\Expr\PreInc::class:
                return new Expression\Operators\PreInc();
            case Node\Expr\PreDec::class:
                return new Expression\Operators\PreDec();
            /**
             * Arithmetical
             */
            case Node\Expr\BinaryOp\Div::class:
                return new Expression\Operators\Arithmetical\Div();
            case Node\Expr\BinaryOp\Plus::class:
                return new Expression\Operators\Arithmetical\Plus();
            case Node\Expr\BinaryOp\Minus::class:
                return new Expression\Operators\Arithmetical\Minus();
            case Node\Expr\BinaryOp\Mul::class:
                return new Expression\Operators\Arithmetical\Mul();
            case Node\Expr\BinaryOp\Mod::class:
                return new Expression\Operators\Arithmetical\Mod();
            case Node\Expr\BinaryOp\Pow::class:
                return new Expression\Operators\Arithmetical\Pow();

            /**
             * Bitwise
             * @link http://php.net/manual/ru/language.operators.bitwise.php
             */
            case Node\Expr\BinaryOp\BitwiseOr::class:
                return new Expression\Operators\Bitwise\BitwiseOr();
            case Node\Expr\BinaryOp\BitwiseXor::class:
                return new Expression\Operators\Bitwise\BitwiseXor();
            case Node\Expr\BinaryOp\BitwiseAnd::class:
                return new Expression\Operators\Bitwise\BitwiseAnd();
            case Node\Expr\BinaryOp\ShiftRight::class:
                return new Expression\Operators\Bitwise\ShiftRight();
            case Node\Expr\BinaryOp\ShiftLeft::class:
                return new Expression\Operators\Bitwise\ShiftLeft();
            case Node\Expr\BitwiseNot::class:
                return new Expression\Operators\Bitwise\BitwiseNot();
            /**
             * Logical
             */
            case Node\Expr\BinaryOp\BooleanOr::class:
                return new Expression\Operators\Logical\BooleanOr();
            case Node\Expr\BinaryOp\BooleanAnd::class:
                return new Expression\Operators\Logical\BooleanAnd();
            case Node\Expr\BooleanNot::class:
                return new Expression\Operators\Logical\BooleanNot();
            case Node\Expr\BinaryOp\LogicalAnd::class:
                return new Expression\Operators\Logical\LogicalAnd();
            case Node\Expr\BinaryOp\LogicalOr::class:
                return new Expression\Operators\Logical\LogicalOr();
            case Node\Expr\BinaryOp\LogicalXor::class:
                return new Expression\Operators\Logical\LogicalXor();

            /**
             * Comparison
             */
            case Node\Expr\BinaryOp\Greater::class:
                return new Expression\Operators\Comparison\Greater();
            case Node\Expr\BinaryOp\GreaterOrEqual::class:
                return new Expression\Operators\Comparison\GreaterOrEqual();
            case Node\Expr\BinaryOp\Smaller::class:
                return new Expression\Operators\Comparison\Smaller();
            case Node\Expr\BinaryOp\SmallerOrEqual::class:
                return new Expression\Operators\Comparison\SmallerOrEqual();

            /**
             * Casts
             */
            case Node\Expr\Cast\Array_::class:
                return new Expression\Casts\ArrayCast();
            case Node\Expr\Cast\Bool_::class:
                return new Expression\Casts\BoolCast();
            case Node\Expr\Cast\Int_::class:
                return new Expression\Casts\IntCast();
            case Node\Expr\Cast\Double::class:
                return new Expression\Casts\DoubleCast();
            case Node\Expr\Cast\Object_::class:
                return new Expression\Casts\ObjectCast();
            case Node\Expr\Cast\String_::class:
                return new Expression\Casts\StringCast();
            case Node\Expr\Cast\Unset_::class:
                return new Expression\Casts\UnsetCast();


            /**
             * Other
             */
            case Node\Expr\Array_::class:
                return new Expression\ArrayOp();
            case Node\Expr\Assign::class:
                return new Expression\Assign();
            case Node\Expr\AssignRef::class:
                return new Expression\AssignRef();
            case Node\Expr\Closure::class:
                return new Expression\Closure();
            case Node\Expr\ConstFetch::class:
                return new Expression\ConstFetch();
            case Node\Expr\ClassConstFetch::class:
                return new Expression\ClassConstFetch();
            case Node\Expr\PropertyFetch::class:
                return new Expression\PropertyFetch();
            case Node\Expr\StaticPropertyFetch::class:
                return new Expression\StaticPropertyFetch();
            case Node\Expr\ArrayDimFetch::class:
                return new Expression\ArrayDimFetch();
            case Node\Expr\UnaryMinus::class:
                return new Expression\Operators\UnaryMinus();
            case Node\Expr\UnaryPlus::class:
                return new Expression\Operators\UnaryPlus();
            case Node\Expr\Exit_::class:
                return new Expression\ExitOp();
            case Node\Expr\Isset_::class:
                return new Expression\IssetOp();
            case Node\Expr\Print_::class:
                return new Expression\PrintOp();
            case Node\Expr\Empty_::class:
                return new Expression\EmptyOp();
            case Node\Expr\Eval_::class:
                return new Expression\EvalOp();
            case Node\Expr\ShellExec::class:
                return new Expression\ShellExec();
            case Node\Expr\ErrorSuppress::class:
                return new Expression\ErrorSuppress();
            case Node\Expr\Include_::class:
                return new Expression\IncludeOp();
            case Node\Expr\Clone_::class:
                return new Expression\CloneOp();
            case Node\Expr\Ternary::class:
                return new Expression\Ternary();
            case Node\Expr\Yield_::class:
                return new Expression\YieldOp();
            case Node\Expr\YieldFrom::class:
                return new Expression\YieldFrom();
            case Node\Expr\Variable::class:
                return new Expression\Variable();
        }

        return false;
    }

    /**
     * @param object|string $expr
     * @throws InvalidArgumentException when $expr is not string/object/null
     * @throws RuntimeException when compiler class does not return a CompiledExpression
     * @return CompiledExpression
     */
    public function compile($expr)
    {
        if (is_string($expr)) {
            return new CompiledExpression(CompiledExpression::STRING, $expr);
        }

        if (is_null($expr)) {
            return new CompiledExpression(CompiledExpression::NULL);
        }

        if (!is_object($expr)) {
            throw new InvalidArgumentException('$expr must be string/object/null');
        }

        if ($expr instanceof Node\Scalar) {
            $scalar = new \PHPSA\Compiler\Scalar($this->context, $this->eventManager);
            return $scalar->compile($expr);
        }

        $this->eventManager->fire(
            ExpressionBeforeCompile::EVENT_NAME,
            new ExpressionBeforeCompile(
                $expr,
                $this->context
            )
        );

        $className = get_class($expr);
        switch ($className) {
            case Node\Arg::class:
                /**
                 * @todo Better compile
                 */
                return $this->compile($expr->value);

            /**
             * Names
             */
            case Node\Name::class:
                return $this->getNodeName($expr);
            case Node\Name\FullyQualified::class:
                return $this->getFullyQualifiedNodeName($expr);
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
     * @todo Implement - does not belong in this file
     *
     * @param Node\Stmt\Property $st
    public function passProperty(Node\Stmt\Property $st)
    {
        $docBlock = $st->getDocComment();
        if (!$docBlock) {
            return new CompiledExpression();
        }

        $phpdoc = DocBlockFactory::createInstance()->create($docBlock->getText());

        $varTags = $phpdoc->getTagsByName('var');
        if ($varTags) {
            $varTag = current($varTags);

            $typeResolver = new \phpDocumentor\Reflection\TypeResolver();

            try {
                $type = $typeResolver->resolve($varTag->getType());
            } catch (\InvalidArgumentException $e) {
                return new CompiledExpression();
            }

            if ($type) {
                switch (get_class($type)) {
                    case \phpDocumentor\Reflection\Types\Object_::class:
                        return new CompiledExpression(
                            CompiledExpression::OBJECT
                        );
                    case \phpDocumentor\Reflection\Types\Integer::class:
                        return new CompiledExpression(
                            CompiledExpression::INTEGER
                        );
                    case \phpDocumentor\Reflection\Types\String_::class:
                        return new CompiledExpression(
                            CompiledExpression::STRING
                        );
                    case \phpDocumentor\Reflection\Types\Float_::class:
                        return new CompiledExpression(
                            CompiledExpression::DOUBLE
                        );
                    case \phpDocumentor\Reflection\Types\Null_::class:
                        return new CompiledExpression(
                            CompiledExpression::NULL
                        );
                    case \phpDocumentor\Reflection\Types\Boolean::class:
                        return new CompiledExpression(
                            CompiledExpression::BOOLEAN
                        );
                }
            }
        }

        return new CompiledExpression();
    }
*/


    /**
     * @param Node\Expr\Variable $expr
     * @param mixed $value
     * @param int $type
     * @return CompiledExpression
     */
    public function declareVariable(Node\Expr\Variable $expr, $value = null, $type = CompiledExpression::UNKNOWN)
    {
        $variable = $this->context->getSymbol($expr->name);
        if (!$variable) {
            $variable = new Variable($expr->name, $value, $type, $this->context->getCurrentBranch());
            $this->context->addVariable($variable);
        }

        return new CompiledExpression($variable->getType(), $variable->getValue(), $variable);
    }

    /**
     * @param Node\Name\FullyQualified $expr
     * @return CompiledExpression
     */
    public function getFullyQualifiedNodeName(Node\Name\FullyQualified $expr)
    {
        $this->context->debug('Unimplemented FullyQualified', $expr);

        return new CompiledExpression();
    }

    /**
     * @param Node\Name $expr
     * @return CompiledExpression
     */
    public function getNodeName(Node\Name $expr)
    {
        $nodeString = $expr->toString();
        if ($nodeString === 'null') {
            return new CompiledExpression(CompiledExpression::NULL);
        }

        if (in_array($nodeString, ['parent'], true)) {
            /** @var ClassDefinition $scope */
            $scope = $this->context->scope;
            assert($scope instanceof ClassDefinition);

            if ($scope->getExtendsClass()) {
                $definition = $scope->getExtendsClassDefinition();
                if ($definition) {
                    return new CompiledExpression(CompiledExpression::OBJECT, $definition);
                }
            } else {
                $this->context->notice(
                    'no-parent',
                    'Cannot access parent:: when current class scope has no parent',
                    $expr
                );
            }
        }

        if (in_array($nodeString, ['self', 'static'], true)) {
            return CompiledExpression::fromZvalValue($this->context->scope);
        }

        if (defined($nodeString)) {
            return CompiledExpression::fromZvalValue(constant($expr));
        }

        return new CompiledExpression(CompiledExpression::STRING, $expr->toString());
    }
}
