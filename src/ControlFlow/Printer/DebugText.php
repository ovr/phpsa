<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Printer;

use PHPSA\ControlFlow\Block;
use PHPSA\ControlFlow\Node\JumpIf;

class DebugText
{
    protected $blocks = [];

    protected function visitBlock(Block $parent)
    {
        $this->blocks[$parent->getId()] = $parent;

        $childrens = $parent->getChildrens();
        if ($childrens) {
            foreach ($childrens as $children) {
                if ($children instanceof JumpIf) {
                    $blocks = $children->getSubBlocks();

                    foreach ($blocks as $name => $block) {
                        if ($block) {
                            $this->blocks[$block->getId()] = $block;

                            $blockExit = $block->getExit();
                            if ($blockExit) {
                                $this->visitBlock($blockExit);
                            }
                        }
                    }
                }
            }
        }

        $exit = $parent->getExit();
        if ($exit) {
            $this->visitBlock($exit);
        }
    }

    public function printGraph(Block $parent)
    {
        $this->visitBlock($parent);

        ksort($this->blocks);

        foreach ($this->blocks as $id => $block) {
            echo 'Block #' . $id . PHP_EOL;

            $childrens = $block->getChildrens();
            if ($childrens) {
                foreach ($childrens as $children) {
                    echo '  ' . get_class($children) . ($children->willExit() ? ' WILL EXIT!! ' : '') . PHP_EOL;

                    if ($children instanceof JumpIf) {
                        $blocks = $children->getSubBlocks();

                        foreach ($blocks as $name => $subBlock) {
                            if ($subBlock) {
                                echo "\t" . $name . ' -> ' . $subBlock->getId() . PHP_EOL;
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
