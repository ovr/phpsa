<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor;

use PHPSA\Context;

interface StatementCompilerInterface
{
    /**
     * @param \PHPParser\Node\Stmt $expr
     * @param Context $context
     * @return mixed
     */
    public function pass($expr, Context $context);
}
