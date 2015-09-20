<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Graph;

class Node
{
    /**
     * @var \PhpParser\Node
     */
    protected $astNode;

    /**
     * @param \PhpParser\Node $astNode
     */
    public function __constructor(\PhpParser\Node $astNode)
    {
        $this->astNode = $astNode;
    }
}
