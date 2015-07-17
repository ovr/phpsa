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

abstract class AbstractDefinition
{
    /**
     * @var string
     */
    protected $name;

    abstract public function compile(Context $context);

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return ScopePointer
     */
    public function getPointer()
    {
        return new ScopePointer($this);
    }
}
