<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Node;

class ThrowNode extends AbstractNode
{
    /**
     * {@inheritdoc}
     */
    public function willExit()
    {
        return true;
    }
}
