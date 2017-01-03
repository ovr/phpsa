<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Node;

class ReturnNode extends AbstractNode
{
    /**
     * {@inheritdoc}
     */
    public function willExit()
    {
        return true;
    }
}
