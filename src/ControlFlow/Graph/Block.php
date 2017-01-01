<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Graph;

class Block
{
    protected $childrens = [];

    protected $exit;

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function addChildren($node)
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
     * @return array
     */
    public function getChildrens()
    {
        return $this->childrens;
    }

    /**
     * @return Block
     */
    public function getExit()
    {
        return $this->exit;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
