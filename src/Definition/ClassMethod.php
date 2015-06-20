<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PHPSA\Context;

class ClassMethod
{
    /**
     * @var string
     */
    protected $name;

    protected $ast;

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
        foreach ($this->ast as $st) {
            $expr = new \PHPSA\Visotor\Statement($st, $context);
        }
    }
}
