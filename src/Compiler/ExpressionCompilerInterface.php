<?php
/**
 * Created by PhpStorm.
 * User: ovr
 * Date: 06.07.15
 * Time: 16:22
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
