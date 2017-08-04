<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow;

use PHPSA\ControlFlow\Node\AbstractNode;

class Block
{
    /**
     * @var bool
     */
    protected $unreachable = false;

    /**
     * @var AbstractNode[]
     */
    protected $children = [];

    /**
     * @var Block[]
     */
    public $parents = [];

    /**
     * @var Block|null
     */
    protected $exit;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    public $label;

    /**
     * @param int  $id
     * @param bool $unreachable
     */
    public function __construct($id, $unreachable = false)
    {
        $this->id = $id;
        $this->unreachable = $unreachable;
    }

    /**
     * @param AbstractNode $node
     */
    public function addChildren(AbstractNode $node)
    {
        $this->children[] = $node;
    }

    /**
     * @param Block $exit
     */
    public function setExit(Block $exit)
    {
        $this->exit = $exit;
    }

    /**
     * @return AbstractNode[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return Block|null
     */
    public function getExit()
    {
        return $this->exit;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Block
     */
    public function addParent(Block $parent)
    {
        $this->parents[] = $parent;
    }

    /**
     * @return bool
     */
    public function isUnreachable()
    {
        return $this->unreachable;
    }
}
