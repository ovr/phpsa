<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Visitor;

use PHPSA\ControlFlow\Block;

/**
 *
 * function test($a) {
 *     return $a;
 *
 *     $a = 1; // Unreachable node!
 * }
 *
 */
class UnreachableVisitor extends AbstractVisitor
{
    /**
     * @param Block $block
     */
    public function enterBlock(Block $block)
    {
        $childrens = $block->getChildrens();
        if ($childrens) {
            $childrensCount = count($childrens);
            if ($childrensCount <= 1) {
                return;
            }

            foreach ($childrens as $index => $children) {
                // Check that exit node is not the latest
                if ($children->willExit() && ($index + 1) != $childrensCount) {
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
