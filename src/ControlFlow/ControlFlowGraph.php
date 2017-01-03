<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow;

use PhpParser\Node\Stmt\Function_;
use PHPSA\ControlFlow\Node\Assign;
use PHPSA\ControlFlow\Node\Exit_;
use PHPSA\ControlFlow\Node\Jump;
use PHPSA\ControlFlow\Node\JumpIf;
use PHPSA\ControlFlow\Node\Return_;
use PHPSA\ControlFlow\Node\Throw_;

class ControlFlowGraph
{
    protected $lastBlockId = 1;

    /**
     * @var Block
     */
    protected $root;

    public function __construct($statement)
    {
        $this->root = new Block($this->lastBlockId++);

        if ($statement instanceof Function_) {
            if ($statement->stmts) {
                $this->passNodes($statement->stmts, $this->root);
            }
        }
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
                case \PhpParser\Node\Stmt\While_::class:
                    $block = $this->passWhile($stmt, $block);
                    break;
                case \PhpParser\Node\Stmt\Throw_::class:
                    $this->passThrow($stmt, $block);
                    break;
                case \PhpParser\Node\Expr\Exit_::class:
                    $block->addChildren(new Exit_());
                    break;
                case \PhpParser\Node\Stmt\Label::class:
                    $block->setExit(
                        $block = new Block($this->lastBlockId++)
                    );
                    $block->label = $stmt->name;
                    break;
                default:
                    echo 'Unimplemented ' . get_class($stmt) . PHP_EOL;
                    break;
            }
        }
    }

    protected function passIf(\PhpParser\Node\Stmt\If_ $if, Block $block)
    {
        $trueBlock = new Block($this->lastBlockId++);
        $this->passNodes($if->stmts, $trueBlock);

        $jumpIf = new JumpIf($trueBlock);

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

    protected function passWhile(\PhpParser\Node\Stmt\While_ $while, Block $block)
    {
        $cond = new Block($this->lastBlockId++);
        $block->setExit(
            $cond
        );

        $loop = new Block($this->lastBlockId++);

        $jumpIf = new JumpIf($loop);
        $cond->addChildren($jumpIf);

        $this->passNodes($while->stmts, $loop);

        $loop->addChildren(new Jump($cond));
        //$loop->setExit($cond);

        $after = new Block($this->lastBlockId++);
        $jumpIf->setElse($after);

        return $after;
    }

    protected function passThrow(\PhpParser\Node\Stmt\Throw_ $throw_, Block $block)
    {
        $block->addChildren(new Throw_());
    }

    protected function passAssign(\PhpParser\Node\Expr\Assign $assign, Block $block)
    {
        $block->addChildren(new Assign());
    }

    protected function passReturn(\PhpParser\Node\Stmt\Return_ $return_, Block $block)
    {
        $block->addChildren(new Return_());
    }

    /**
     * @return Block
     */
    public function getRoot()
    {
        return $this->root;
    }
}
