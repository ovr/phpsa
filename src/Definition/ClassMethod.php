<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Parameter;
use PHPSA\Compiler\Types;
use PHPSA\Compiler\Event;
use PHPSA\Context;

class ClassMethod extends AbstractDefinition
{
    protected $type;

    /**
     * @var Node\Stmt\ClassMethod
     */
    protected $statement;

    /**
     * Return type
     *
     * @var int
     */
    protected $returnType = CompiledExpression::VOID;

    /**
     * Array of possible return values
     *
     * @var array
     */
    protected $possibleReturnValues = array();

    /**
     * @param string $name
     * @param Node\Stmt\ClassMethod $statement
     * @param integer $type
     */
    public function __construct($name, Node\Stmt\ClassMethod $statement, $type)
    {
        $this->name = $name;
        $this->statement = $statement;
        $this->type = $type;
    }

    /**
     * @param Context $context
     * @return boolean|null
     */
    public function compile(Context $context)
    {
        $context->getEventManager()->fire(
            Event\StatementBeforeCompile::EVENT_NAME,
            new Event\StatementBeforeCompile(
                $this->statement,
                $context
            )
        );

        $this->compiled = true;
        $context->scopePointer = $this->getPointer();

        if ($this->statement->getDocComment() === null) {
            $context->notice(
                'missing-docblock',
                sprintf('Missing docblock for %s() method', $this->name),
                $this->statement
            );
        }

        /**
         * It's not needed to compile empty method via it's abstract
         */
        if ($this->isAbstract()) {
            /** @var ClassDefinition $scope */
            $scope = $context->scope;
            if (!$scope->isAbstract()) {
                $context->notice(
                    'not-abstract-class-with-abstract-method',
                    'Class must be an abstract',
                    $this->statement
                );
            }

            return true;
        }

        if (count($this->statement->stmts) == 0) {
            return $context->notice(
                'not-implemented-method',
                sprintf('Method %s() is not implemented', $this->name),
                $this->statement
            );
        }

        if ($this->statement->params) {
            foreach ($this->statement->params as $parameter) {
                $type = CompiledExpression::UNKNOWN;

                if ($parameter->type) {
                    if (is_string($parameter->type)) {
                        $type = Types::getType($parameter->type);
                    } elseif ($parameter->type instanceof Node\Name\FullyQualified) {
                        $type = CompiledExpression::OBJECT;
                    }
                }

                $context->addVariable(
                    new Parameter($parameter->name, null, $type, $parameter->byRef)
                );
            }
        }

        foreach ($this->statement->stmts as $st) {
            \PHPSA\nodeVisitorFactory($st, $context);
        }
    }

    /**
     * @param Context $context
     * @param Node\Arg[] $args
     * @return CompiledExpression
     * @throws \PHPSA\Exception\RuntimeException
     */
    public function run(Context $context, array $args = null)
    {
        if ($args) {
            foreach ($args as $argument) {
                $arguments[] = $context->getExpressionCompiler()->compile($argument->value);
            }
        }

        return new CompiledExpression();
    }

    /**
     * @return bool
     */
    public function isAbstract()
    {
        return (bool) ($this->type & Node\Stmt\Class_::MODIFIER_ABSTRACT);
    }

    /**
     * @return bool
     */
    public function isStatic()
    {
        return (bool) ($this->type & Node\Stmt\Class_::MODIFIER_STATIC);
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return ($this->type & Node\Stmt\Class_::MODIFIER_PUBLIC) !== 0 || $this->type === 0;
    }

    /**
     * @return bool
     */
    public function isProtected()
    {
        return (bool) ($this->type & Node\Stmt\Class_::MODIFIER_PROTECTED);
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return (bool) ($this->type & Node\Stmt\Class_::MODIFIER_PRIVATE);
    }

    /**
     * @param integer $newType
     */
    public function addNewType($newType)
    {
        if ($this->returnType != CompiledExpression::VOID) {
            $this->returnType = $this->returnType | $newType;
        } else {
            $this->returnType = $newType;
        }
    }

    /**
     * @return int
     */
    public function getReturnType()
    {
        return $this->returnType;
    }

    /**
     * @param $value
     */
    public function addReturnPossibleValue($value)
    {
        $this->possibleReturnValues[] = $value;
    }

    /**
     * @return array
     */
    public function getPossibleReturnValues()
    {
        return $this->possibleReturnValues;
    }
}
