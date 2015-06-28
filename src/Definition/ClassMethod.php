<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PhpParser\Node;
use PHPSA\Context;
use PHPSA\Visitor;

class ClassMethod
{
    /**
     * @var string
     */
    protected $name;

    protected $ast;

    protected $type;

    /**
     * @var Node\Stmt\ClassMethod
     */
    protected $st;

    public function __construct($name, $ast, $type, Node\Stmt\ClassMethod $st)
    {
        $this->name = $name;
        $this->ast = $ast;
        $this->type = $type;
        $this->st = $st;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Context $context
     * @return bool
     */
    public function compile(Context $context)
    {
        if ($this->st->getDocComment() === null) {
            return $context->notice(
                'missing-docblock',
                sprintf('Missing docblock for %s() method', $this->name),
                $this->st
            );
        }

        if (count($this->ast) == 0) {
            return $context->notice(
                'not-implemented-method',
                sprintf('Method %s() is not implemented', $this->name),
                $this->st
            );
        }

        if (count($this->st->params) > 0) {
            /** @var  Node\Param $parameter */
            foreach ($this->st->params as $parameter) {
                $context->addSymbol($parameter->name);
            }
        }

        foreach ($this->ast as $st) {
            $result = \PHPSA\nodeVisitorFactory($st, $context);
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
