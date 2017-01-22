<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Visitor;

use PHPSA\ControlFlow\Block;
use PHPSA\ControlFlow\Node\AbstractNode;

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
        $children = $block->getChildren();
        if ($children) {
            $childrenCount = count($children);
            if ($childrenCount <= 1) {
                return;
            }

            foreach ($children as $index => $child) {
                // Check that exit node is not the latest
                if ($child->willExit() && ($index + 1) != $childrenCount) {
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

    /**
     * @param AbstractNode $block
     */
    public function enterNode(AbstractNode $block)
    {
    }
}
