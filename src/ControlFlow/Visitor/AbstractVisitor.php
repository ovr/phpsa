<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Visitor;

use PHPSA\ControlFlow\Block;
use PHPSA\ControlFlow\Node\AbstractNode;

abstract class AbstractVisitor
{
    /**
     * @param Block $block
     */
    abstract public function enterBlock(Block $block);

    /**
     * @param Block $block
     */
    abstract public function leaveBlock(Block $block);

    /**
     * @param AbstractNode $block
     */
    abstract public function enterNode(AbstractNode $block);
}
