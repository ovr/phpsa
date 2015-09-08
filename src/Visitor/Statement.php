<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor;

use PHPSA\Context;
use PhpParser\Node;
use PHPSA\Definition\ClassMethod;
use PHPSA\Visitor\Statement\AbstractCompiler;
use RuntimeException;

class Statement
{
    /**
     * @param $stmt
     * @return AbstractCompiler
     */
    protected function factory($stmt)
    {
        switch (get_class($stmt)) {
            case 'PhpParser\Node\Stmt\Return_':
                return new Statement\Return_();
                break;
            case 'PhpParser\Node\Stmt\While_':
                return new Statement\While_();
                break;
            case 'PhpParser\Node\Stmt\Switch_':
                return new Statement\Switch_();
                break;
            case 'PhpParser\Node\Stmt\If_':
                return new Statement\If_();
                break;
            case 'PhpParser\Node\Stmt\For_':
                return new Statement\For_();
                break;
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
