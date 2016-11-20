<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Compiler\Parameter;
use PHPSA\Compiler\Types;
use PHPSA\Compiler\Event;
use PHPSA\Context;

/**
 * Class Method Definition
 */
class ClassMethod extends AbstractDefinition
{
    /**
     * Contains a number representing all modifiers (public, static, ...)
     *
     * @var int
     */
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
    protected $possibleReturnValues = [];

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

        /**
         * It's not needed to compile empty method via it's abstract
         */
        if ($this->isAbstract()) {
            /** @var ClassDefinition $scope */
            $scope = $context->scope;
            if (!$scope->isAbstract()) {
                $context->notice(
                    'not-abstract-class-with-abstract-method',
                    'Class must be abstract',
                    $this->statement
                );
            }

            return true;
        }

        if ($this->statement->params) {
            foreach ($this->statement->params as $parameter) {
                $type = CompiledExpression::UNKNOWN;

                if ($parameter->type) {
                    if (is_string($parameter->type)) {
                        $type = Types::getType($parameter->type);
                    } elseif ($parameter->type instanceof Node\Name) {
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
     * @param CompiledExpression[] $args
     * @return CompiledExpression
     * @throws \PHPSA\Exception\RuntimeException
     */
    public function run(Context $context, array $args = null)
    {
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

    /**
    * @return Node\Param[]
    */
    public function getParams()
    {
        return $this->statement->params;
    }

    /**
     * @return int
     */
    public function getModifier()
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setModifier($type)
    {
        $this->type = $type;
    }
}
