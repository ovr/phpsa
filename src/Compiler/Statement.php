<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler;

use PHPSA\Context;
use PhpParser\Node;
use PHPSA\Compiler\Statement\AbstractCompiler;
use RuntimeException;

class Statement
{
    /**
     * @param Node\Stmt $stmt
     * @return AbstractCompiler
     */
    protected function factory($stmt)
    {
        switch (get_class($stmt)) {
            case 'PhpParser\Node\Stmt\Return_':
                return new Statement\ReturnSt();
            case 'PhpParser\Node\Stmt\While_':
                return new Statement\WhileSt();
            case 'PhpParser\Node\Stmt\Switch_':
                return new Statement\SwitchSt();
            case 'PhpParser\Node\Stmt\If_':
                return new Statement\IfSt();
            case 'PhpParser\Node\Stmt\For_':
                return new Statement\ForSt();
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
            $compiler = $this->factory($stmt);
        } catch (\Exception $e) {
            $context->debug('StatementCompiler is not implemented for ' . get_class($stmt));
            return;
        }

        $compiler->pass($stmt, $context);
    }
}
