<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\ScopePointer;
use PHPSA\Variable;

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
     * @param $name
     * @param Node\Stmt\ClassMethod $statement
     * @param $type
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

        if (count($this->statement->params) > 0) {
            /** @var  Node\Param $parameter */
            foreach ($this->statement->params as $parameter) {
                $context->addSymbol($parameter->name);
            }
        }

        $thisPtr = new Variable('this', $this, CompiledExpression::OBJECT);
        $thisPtr->incGets();
        $context->addVariable($thisPtr);

        foreach ($this->statement->stmts as $st) {
            \PHPSA\nodeVisitorFactory($st, $context);
        }
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
     * @param $newType
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
