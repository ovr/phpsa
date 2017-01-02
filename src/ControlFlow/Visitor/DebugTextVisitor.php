<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Visitor;

use PHPSA\ControlFlow\Block;

class DebugTextVisitor extends AbstractVisitor
{
    /**
     * @param Block $block
     */
    public function enterBlock(Block $block)
    {
        echo 'Enter Block ' . $block->getId() . PHP_EOL;
    }

    /**
     * @param Block $block
     */
    public function leaveBlock(Block $block)
    {
        echo 'Leave Block ' . $block->getId() . PHP_EOL;
    }
}
