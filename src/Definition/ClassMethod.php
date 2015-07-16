<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Variable;

class ClassMethod extends AbstractDefinition
{
    protected $type;

    /**
     * @var Node\Stmt\ClassMethod
     */
    protected $statement;

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
        if ($this->statement->getDocComment() === null) {
            $context->notice(
                'missing-docblock',
                sprintf('Missing docblock for %s() method', $this->name),
                $this->statement
            );
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
}
