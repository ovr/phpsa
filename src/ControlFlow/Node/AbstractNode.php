<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Node;

abstract class AbstractNode
{
    /**
     * Will this node exit?
     *
     * @return bool
     */
    public function willExit()
    {
        return false;
    }

    /**
     * @return array
     */
    public function getSubVariables()
    {
        return [];
    }

    /**
     * @return \PHPSA\ControlFlow\Block[]
     */
    public function getSubBlocks()
    {
        return [];
    }
}
