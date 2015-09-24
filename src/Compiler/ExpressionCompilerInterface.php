<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler;

use PHPSA\Context;

interface ExpressionCompilerInterface
{
    /**
     * @param $expr
     * @param Context $context
     * @return \PHPSa\CompiledExpression
     */
    public function pass($expr, Context $context);
}
