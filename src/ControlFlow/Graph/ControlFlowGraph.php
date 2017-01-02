<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow\Graph;

use PhpParser\Node\Stmt\Function_;
use PHPSA\ControlFlow\Node\Assign;
use PHPSA\ControlFlow\Node\Exit_;
use PHPSA\ControlFlow\Node\JumpIf;
use PHPSA\ControlFlow\Node\Return_;

class ControlFlowGraph
{
    protected $root;

    protected $lastBlockId = 1;

    public function __construct($statement)
    {
        $block = new Block($this->lastBlockId++);

        if ($statement instanceof Function_) {
            if ($statement->stmts) {
                $this->passNodes($statement->stmts, $block);
            }
        }

        $printer = new \PHPSA\ControlFlow\Printer\DebugText();
        $printer->printGraph($block);
    }

    protected function passNodes(array $nodes, Block $block)
    {
        foreach ($nodes as $stmt) {
            switch (get_class($stmt)) {
                case \PhpParser\Node\Expr\Assign::class:
                    $this->passAssign($stmt, $block);
                    break;
                case \PhpParser\Node\Stmt\Return_::class:
                    $this->passReturn($stmt, $block);
                    break;
                case \PhpParser\Node\Stmt\For_::class:
                    $block = $this->passFor($stmt, $block);
                    break;
                case \PhpParser\Node\Stmt\If_::class:
                    $block = $this->passIf($stmt, $block);
                    break;
                case \PhpParser\Node\Expr\Exit_::class:
                    $block->addChildren(new Exit_());
                    break;
                default:
                    echo 'Unimplemented ' . get_class($stmt) . PHP_EOL;
                    break;
            }
        }
    }

    protected function passIf(\PhpParser\Node\Stmt\If_ $if, Block $block)
    {
        $jumpIf = new JumpIf($this->lastBlockId++);

        $trueBlock = new Block($this->lastBlockId++);
        $this->passNodes($if->stmts, $trueBlock);

        $jumpIf->setIf($trueBlock);

        $elseBlock = null;

        if ($if->else) {
            if ($if->else->stmts) {
                $elseBlock = new Block($this->lastBlockId++);
                $this->passNodes($if->else->stmts, $elseBlock);

                $jumpIf->setElse($elseBlock);
            }
        }

        $block->addChildren(
            $jumpIf
        );

        $exitBlock = new Block($this->lastBlockId++);
        $trueBlock->setExit($exitBlock);

        if ($elseBlock) {
            $elseBlock->setExit($exitBlock);
        }

        return $exitBlock;
    }


    protected function passFor(\PhpParser\Node\Stmt\For_ $for, Block $block)
    {
        $this->passNodes($for->init, $block);

        $block->setExit(
            $loop = new Block($this->lastBlockId++)
        );
        $this->passNodes($for->stmts, $loop);

        $loop->setExit(
            $after = new Block($this->lastBlockId++)
        );
        return $after;
    }

    protected function passAssign(\PhpParser\Node\Expr\Assign $assign, Block $block)
    {
        $block->addChildren(new Assign());
    }

    protected function passReturn(\PhpParser\Node\Stmt\Return_ $return_, Block $block)
    {
        $block->addChildren(new Return_());
    }
}
