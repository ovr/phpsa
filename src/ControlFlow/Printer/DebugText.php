<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Printer;

use PHPSA\ControlFlow\Block;

class DebugText
{
    /**
     * @var Block[]
     */
    protected $blocks = [];

    protected function visitBlock(Block $parent, $level = 0)
    {
        if ($level > 1) {
            return false;
        }

        $this->blocks[$parent->getId()] = $parent;

        $childrens = $parent->getChildrens();
        if ($childrens) {
            foreach ($childrens as $children) {
                $subBlocks = $children->getSubBlocks();
                if ($subBlocks) {
                    foreach ($subBlocks as $name => $block) {
                        if ($block) {
                            $this->blocks[$block->getId()] = $block;

                            $blockExit = $block->getExit();
                            if ($blockExit) {
                                $this->visitBlock($blockExit, $level + 1);
                            }
                        }
                    }
                }
            }
        }

        $exit = $parent->getExit();
        if ($exit) {
            $this->visitBlock($exit, 0);
        }
    }

    public function printGraph(Block $parent)
    {
        $this->visitBlock($parent);

        ksort($this->blocks);

        foreach ($this->blocks as $id => $block) {
            echo 'Block #' . $id . ($block->label ? ' Label: ' . $block->label : '') . PHP_EOL;

            $childrens = $block->getChildrens();
            if ($childrens) {
                foreach ($childrens as $children) {
                    echo '  ' . get_class($children) . ($children->willExit() ? ' WILL EXIT!! ' : '') . PHP_EOL;

                    $subBlocks = $children->getSubBlocks();
                    if ($subBlocks) {
                        foreach ($subBlocks as $name => $subBlock) {
                            if ($subBlock) {
                                echo "\t" . $name . ' -> ' . $subBlock->getId() . PHP_EOL;
                            } else {
                                echo "\t" . $name . ' -> NOTHING';
                            }
                        }
                    }
                }
            }

            $exit = $block->getExit();
            if ($exit) {
                echo '  -> ' . $exit->getId() . PHP_EOL;
            }

            echo PHP_EOL . PHP_EOL;
        }
    }
}
