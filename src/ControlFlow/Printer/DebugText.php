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

        $children = $parent->getChildren();
        if ($children) {
            foreach ($children as $child) {
                $subBlocks = $child->getSubBlocks();
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
            echo 'Block#' . $id . ($block->label ? ' Label: ' . $block->label : '') . PHP_EOL;

            $children = $block->getChildren();
            if ($children) {
                foreach ($children as $child) {
                    echo '  ' . get_class($child) . ($child->willExit() ? ' WILL EXIT!! ' : '') . PHP_EOL;

                    $subVariables = $child->getSubVariables();
                    if ($subVariables) {
                        foreach ($subVariables as $name => $subVariable) {
                            if ($subVariable) {
                                echo "\t" . $name . ' -> ' . get_class($subVariable) . PHP_EOL;
                            } else {
                                echo "\t" . $name . ' -> NOTHING';
                            }
                        }
                    }

                    $subBlocks = $child->getSubBlocks();
                    if ($subBlocks) {
                        foreach ($subBlocks as $name => $subBlock) {
                            if ($subBlock) {
                                echo "\t" . $name . ' -> Block#' . $subBlock->getId() . PHP_EOL;
                            } else {
                                echo "\t" . $name . ' -> NOTHING';
                            }
                        }
                    }
                }
            }

            $exit = $block->getExit();
            if ($exit) {
                echo '  -> Block#' . $exit->getId() . PHP_EOL;
            }

            echo PHP_EOL . PHP_EOL;
        }
    }
}
