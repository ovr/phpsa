<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PHPSA\ScopePointer;

/**
 * Abstract Definition with namespace added
 */
abstract class ParentDefinition extends AbstractDefinition
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var \PHPSA\ControlFlow\Graph\ControlFlowGraph
     */
    protected $cfg;

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return \PHPSA\ControlFlow\Graph\ControlFlowGraph
     */
    public function getCFG()
    {
        return $this->cfg;
    }
}
