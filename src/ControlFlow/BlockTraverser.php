<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow;

use PHPSA\ControlFlow\Visitor\AbstractVisitor;

class BlockTraverser
{
    /**
     * @var AbstractVisitor[]
     */
    protected $visitors = [];

    public function addVisitor(AbstractVisitor $visitor)
    {
        $this->visitors[] = $visitor;
    }

    public function traverse(ControlFlowGraph $cfg)
    {
        $rootBlock = $cfg->getRoot();

        /** @var AbstractVisitor $visitor */
        foreach ($this->visitors as $visitor) {
            $visitor->enterBlock($rootBlock);
        }

        $children = $rootBlock->getChildren();
        if ($children) {
            foreach ($children as $child) {
                foreach ($this->visitors as $visitor) {
                    $visitor->enterNode($child);
                }
            }
        }

        /** @var AbstractVisitor $visitor */
        foreach ($this->visitors as $visitor) {
            $visitor->leaveBlock($rootBlock);
        }
    }
}
