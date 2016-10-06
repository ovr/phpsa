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
     * @throws RuntimeException
     * @return AbstractCompiler
     */
    protected function factory($stmt)
    {
        switch (get_class($stmt)) {
            case Stmt\Break_::class:
                return new Statement\BreakSt();
            case Stmt\Case_::class:
                return new Statement\CaseSt();
            case Stmt\Continue_::class:
                return new Statement\ContinueSt();
            case Stmt\Echo_::class:
                return new Statement\EchoSt();
            case Stmt\Return_::class:
                return new Statement\ReturnSt();
            case Stmt\While_::class:
                return new Statement\WhileSt();
            case Stmt\Switch_::class:
                return new Statement\SwitchSt();
            case Stmt\If_::class:
                return new Statement\IfSt();
            case Stmt\ElseIf_::class:
                return new Statement\ElseIfSt();
            case Stmt\Else_::class:
                return new Statement\ElseSt();
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
            case Stmt\Throw_::class:
                return new Statement\ThrowSt();
            case Stmt\Global_::class:
                return new Statement\GlobalSt();
            case Stmt\Static_::class:
                return new Statement\StaticSt();
            case Stmt\Declare_::class:
                return new Statement\DeclareSt();
            case Stmt\Const_::class:
                return new Statement\ConstSt();
            case Stmt\Unset_::class:
                return new Statement\UnsetSt();
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
            $context->getEventManager()->fire(
                Event\StatementBeforeCompile::EVENT_NAME,
                new Event\StatementBeforeCompile(
                    $stmt,
                    $context
                )
            );
            
            if ($stmt instanceof Stmt\Goto_ || $stmt instanceof Stmt\Label || $stmt instanceof Stmt\InlineHTML || $stmt instanceof Stmt\Nop) {
                return;
            }

            $compiler = $this->factory($stmt);
        } catch (\Exception $e) {
            $context->debug('StatementCompiler is not implemented for ' . get_class($stmt));
            return;
        }

        $compiler->pass($stmt, $context);
    }
}
