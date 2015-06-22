<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor;

use PHPSA\Context;
use PhpParser\Node;

class Statement
{
    /**
     * @var Context
     */
    protected $context;

    protected function passReturn(Node\Stmt\Return_ $st)
    {
        $expression = new Expression($st->expr, $this->context);
        $compiledExpression = $expression->compile($st->expr);
    }

    public function __construct(Node\Stmt $stmt, Context $context)
    {
        $this->context = $context;

        switch (get_class($stmt)) {
            case 'PhpParser\Node\Stmt\Return_':
                $this->passReturn($stmt);
                break;
            default:
                var_dump(get_class($stmt));
                break;
        }
    }
}