<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PhpParser\Node\Stmt\Class_;
use PHPSA\Context;

class ClassMethod
{
    /**
     * @var string
     */
    protected $name;

    protected $ast;

    protected $type;

    public function __construct($name, $ast)
    {
        $this->name = $name;
        $this->ast = $ast;
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
            return true;
        }

        foreach ($this->ast as $st) {
            if ($st instanceof \PhpParser\Node\Stmt) {
                $expr = new \PHPSA\Visotor\Statement($st, $context);
            } else {
                $expr = new \PHPSA\Visotor\Expression($st, $context);
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
