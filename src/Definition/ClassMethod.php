<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PhpParser\Node\Stmt\Class_;
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
     * @var \PhpParser\Node\Stmt\ClassMethod
     */
    protected $st;

    public function __construct($name, $ast, $type, \PhpParser\Node\Stmt\ClassMethod $st)
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

    public function compile(Context $context)
    {
        if (count($this->ast) == 0) {
            return $context->notice(
                'not-implemented-method',
                sprintf('Method %s() is not implemented', $this->name),
                $this->st
            );
        }

        if (count($this->st->params) > 0) {
            /** @var  \PhpParser\Node\Param $parameter */
            foreach ($this->st->params as $parameter) {
                $context->addSymbol($parameter->name);
            }
        }

        foreach ($this->ast as $st) {
            if ($st instanceof \PhpParser\Node\Stmt) {
                $expr = new Visitor\Statement($st, $context);
            } else {
                $expr = new Visitor\Expression($st, $context);
                $expr->compile($st);
            }
        }
    }

    /**
     * @return bool
     */
    public function isStatic()
    {
        return (bool) ($this->type & Class_::MODIFIER_STATIC);
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return ($this->type & Class_::MODIFIER_PUBLIC) !== 0 || $this->type === 0;
    }

    /**
     * @return bool
     */
    public function isProtected()
    {
        return (bool) ($this->type & Class_::MODIFIER_PROTECTED);
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return (bool) ($this->type & Class_::MODIFIER_PRIVATE);
    }
}
