<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Graph;

use PHPSA\ControlFlow\Node\AbstractNode;

class Block
{
    /**
     * @var AbstractNode[]
     */
    protected $childrens = [];

    /**
     * @var Block|null
     */
    protected $exit;

    /**
     * @var int
     */
    protected $id;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param AbstractNode $node
     */
    public function addChildren(AbstractNode $node)
    {
        $this->childrens[] = $node;
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
    public function getChildrens()
    {
        return $this->childrens;
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
}
