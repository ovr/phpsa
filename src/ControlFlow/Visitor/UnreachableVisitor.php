<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Visitor;

use PHPSA\ControlFlow\Block;

class UnreachableVisitor extends AbstractVisitor
{
    /**
     * @param Block $block
     */
    public function enterBlock(Block $block)
    {
        $childrens = $block->getChildrens();
        if ($childrens) {
            foreach ($childrens as $children) {
                if ($children->willExit() && count($childrens) > 1) {
                    echo 'Unreacheable block ' . $block->getId() . PHP_EOL;
                }
            }
        }
    }

    /**
     * @param Block $block
     */
    public function leaveBlock(Block $block)
    {
    }
}
