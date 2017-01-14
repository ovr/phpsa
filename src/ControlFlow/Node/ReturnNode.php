<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Node;

use PHPSA\ControlFlow\Node\AbstractNode;

class ReturnNode extends AbstractNode
{
    /**
     * @var AbstractNode|null
     */
    public $expr;

    public function __construct(AbstractNode $node = null)
    {
        $this->expr = $node;
    }

    /**
     * {@inheritdoc}
     */
    public function willExit()
    {
        return true;
    }

    /**
     * @return array
     */
    public function getSubVariables()
    {
        return [
            'expr' => $this->expr
        ];
    }
}
