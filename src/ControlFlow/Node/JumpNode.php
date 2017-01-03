<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Node;

use PHPSA\ControlFlow\Block;

class JumpNode extends AbstractNode
{
    /**
     * @var Block
     */
    protected $exit;

    public function __construct(Block $exit)
    {
        $this->exit = $exit;
    }

    public function getSubBlocks()
    {
        return [
            'exit' => $this->exit
        ];
    }
}
