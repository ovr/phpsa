<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\ControlFlow;

use PhpParser\Node\Stmt\Function_;
use PHPSA\ControlFlow\Node;

class ControlFlowGraph
{
    /**
     * @var int
     */
    protected $lastBlockId = 1;

    /**
     * @var Block
     */
    protected $root;

    /**
     * @var array
     */
    protected $labels;

    /**
     * @var array
     */
    protected $unresolvedGotos;

    /**
     * @param $statement
     */
    public function __construct($statement)
    {
        $this->root = new Block($this->lastBlockId++);

        if ($statement instanceof Function_) {
            if ($statement->stmts) {
                $this->passNodes($statement->stmts, $this->root);
            }
        }
    }

    /**
     * @param array $nodes
     * @param Block $block
     */
    protected function passNodes(array $nodes, Block $block)
    {
        foreach ($nodes as $stmt) {
            switch (get_class($stmt)) {
                case \PhpParser\Node\Stmt\Goto_::class:
                    if (isset($this->labels[$stmt->name])) {
                        $block->addChildren(
                            new Node\JumpNode($this->labels[$stmt->name])
                        );
                    } else {
                        $this->unresolvedGotos[] = $stmt;
                    }
                    break;
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
                case \PhpParser\Node\Stmt\Do_::class:
                    $block = $this->passDo($stmt, $block);
                    break;
                case \PhpParser\Node\Stmt\Throw_::class:
                    $this->passThrow($stmt, $block);
                    break;
                case \PhpParser\Node\Expr\Exit_::class:
                    $block->addChildren(new Node\ExitNode());
                    break;
                case \PhpParser\Node\Stmt\Label::class:
                    $block = $this->createNewBlockIfNeeded($block);
                    $block->label = $stmt->name;
                    $this->labels[$block->label] = $block;
                    break;
                case \PhpParser\Node\Stmt\TryCatch::class:
                    $block = $this->passTryCatch($stmt, $block);
                    break;
                case \PhpParser\Node\Stmt\Nop::class:
                    // ignore commented code
                    break;
                default:
                    echo 'Unimplemented ' . get_class($stmt) . PHP_EOL;
                    break;
            }
        }
    }

    /**
     * If current block is not empty, lets create a new one
     *
     * @param Block $block
     * @return Block
     */
    protected function createNewBlockIfNeeded(Block $block)
    {
        if ($block->getChildrens()) {
            $block->setExit(
                $block = new Block($this->lastBlockId++)
            );
        }

        return $block;
    }

    /**
     * @param \PhpParser\Node\Expr $expr
     * @return Node\AbstractNode
     */
    protected function passExpr(\PhpParser\Node\Expr $expr)
    {
        switch (get_class($expr)) {
            case \PhpParser\Node\Expr\BinaryOp\NotIdentical::class:
                return new Node\Expr\BinaryOp\NotIdentical();

            case \PhpParser\Node\Expr\BinaryOp\Identical::class:
                return new Node\Expr\BinaryOp\Identical();

            case \PhpParser\Node\Expr\BinaryOp\NotEqual::class:
                return new Node\Expr\BinaryOp\NotEqual();

            case \PhpParser\Node\Expr\BinaryOp\Equal::class:
                return new Node\Expr\BinaryOp\Equal();

            case \PhpParser\Node\Expr\BinaryOp\Smaller::class:
                return new Node\Expr\BinaryOp\Smaller();

            case \PhpParser\Node\Expr\BinaryOp\SmallerOrEqual::class:
                return new Node\Expr\BinaryOp\SmallerOrEqual();

            case \PhpParser\Node\Expr\BinaryOp\Greater::class:
                return new Node\Expr\BinaryOp\Greater();

            case \PhpParser\Node\Expr\BinaryOp\GreaterOrEqual::class:
                return new Node\Expr\BinaryOp\GreaterOrEqual();

            case \PhpParser\Node\Expr\Instanceof_::class:
                return new Node\Expr\InstanceOfExpr();

            default:
                echo 'Unimplemented ' . get_class($expr) . PHP_EOL;
        }

        return new Node\UnknownNode();
    }

    /**
     * @param \PhpParser\Node\Stmt\If_ $if
     * @param Block $block
     * @return Block
     */
    protected function passIf(\PhpParser\Node\Stmt\If_ $if, Block $block)
    {
        $trueBlock = new Block($this->lastBlockId++);
        $this->passNodes($if->stmts, $trueBlock);

        $jumpIf = new Node\JumpIfNode($this->passExpr($if->cond), $trueBlock);

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

    /**
     * @param \PhpParser\Node\Stmt\For_ $for
     * @param Block $block
     * @return Block
     */
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

    /**
     * @param \PhpParser\Node\Stmt\Do_ $do
     * @param Block $block
     * @return Block
     */
    protected function passDo(\PhpParser\Node\Stmt\Do_ $do, Block $block)
    {
        $loop = new Block($this->lastBlockId++);
        $this->passNodes($do->stmts, $loop);

        $block->setExit($loop);

        $cond = new Block($this->lastBlockId++);
        $loop->setExit($cond);

        $jumpIf = new Node\JumpIfNode($this->passExpr($do->cond), $loop);
        $cond->addChildren($jumpIf);

        $exitBlock = new Block($this->lastBlockId++);
        $jumpIf->setElse($exitBlock);

        return $exitBlock;
    }

    /**
     * @param \PhpParser\Node\Stmt\While_ $while
     * @param Block $block
     * @return Block
     */
    protected function passWhile(\PhpParser\Node\Stmt\While_ $while, Block $block)
    {
        $cond = new Block($this->lastBlockId++);
        $block->setExit(
            $cond
        );

        $loop = new Block($this->lastBlockId++);

        $jumpIf = new Node\JumpIfNode($this->passExpr($while->cond), $loop);
        $cond->addChildren($jumpIf);

        $this->passNodes($while->stmts, $loop);

        $loop->addChildren(new Node\JumpNode($cond));
        //$loop->setExit($cond);

        $after = new Block($this->lastBlockId++);
        $jumpIf->setElse($after);

        return $after;
    }

    /**
     * @param \PhpParser\Node\Stmt\Throw_ $throw_
     * @param Block $block
     */
    protected function passThrow(\PhpParser\Node\Stmt\Throw_ $throw_, Block $block)
    {
        $block->addChildren(new Node\ThrowNode());
    }

    /**
     * @param \PhpParser\Node\Expr\Assign $assign
     * @param Block $block
     */
    protected function passAssign(\PhpParser\Node\Expr\Assign $assign, Block $block)
    {
        $block->addChildren(new Node\AssignNode());
    }

    /**
     * @param \PhpParser\Node\Stmt\Return_ $return
     * @param Block $block
     */
    protected function passReturn(\PhpParser\Node\Stmt\Return_ $return, Block $block)
    {
        if ($return->expr) {
            $block->addChildren(
                new Node\ReturnNode(
                    $this->passExpr(
                        $return->expr
                    )
                )
            );
        } else {
            $block->addChildren(
                new Node\ReturnNode()
            );
        }
    }

    /**
     * @param \PhpParser\Node\Stmt\TryCatch $stmt
     * @param Block $block
     * @return Block
     */
    protected function passTryCatch(\PhpParser\Node\Stmt\TryCatch $stmt, Block $block)
    {
        $try = new Block($this->lastBlockId++);
        $this->passNodes($stmt->stmts, $try);

        $block->setExit($try);

        if ($stmt->finally) {
            $finally = new Block($this->lastBlockId++);
            $this->passNodes($stmt->finally->stmts, $finally);

            $try->setExit($finally);
            return $finally;
        }

        return $try;
    }

    /**
     * @return Block
     */
    public function getRoot()
    {
        return $this->root;
    }
}
