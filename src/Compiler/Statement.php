<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler;

use PHPSA\Context;
use PhpParser\Node;
use PHPSA\Compiler\Statement\AbstractCompiler;
use RuntimeException;
use PhpParser\Node\Stmt;

class Statement
{
    /**
     * @param Node\Stmt $stmt
     * @return AbstractCompiler
     */
    protected function factory($stmt)
    {
        switch (get_class($stmt)) {
            case Stmt\Return_::class:
                return new Statement\ReturnSt();
            case Stmt\While_::class:
                return new Statement\WhileSt();
            case Stmt\Switch_::class:
                return new Statement\SwitchSt();
            case Stmt\If_::class:
                return new Statement\IfSt();
            case Stmt\Do_::class:
                return new Statement\DoSt();
            case Stmt\For_::class:
                return new Statement\ForSt();
            case Stmt\Foreach_::class:
                return new Statement\ForeachSt();
            case Stmt\TryCatch::class:
                return new Statement\TryCatchSt();
            case Stmt\Catch_::class:
                return new Statement\CatchSt();
        }

        throw new RuntimeException('Unknown statement: ' . get_class($stmt));
    }

    /**
     * @param Node\Stmt $stmt
     * @param Context $context
     */
    public function __construct(Node\Stmt $stmt, Context $context)
    {
        try {
            /**
             * @todo Think a little bit more about own statement for break;
             */
            if ($stmt instanceof Stmt\Break_) {
                return;
            }

            $context->getEventManager()->fire(
                Event\StatementBeforeCompile::EVENT_NAME,
                new Event\StatementBeforeCompile(
                    $stmt,
                    $context
                )
            );
            
            $compiler = $this->factory($stmt);
        } catch (\Exception $e) {
            $context->debug('StatementCompiler is not implemented for ' . get_class($stmt));
            return;
        }

        $compiler->pass($stmt, $context);
    }
}
