<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PHPSA\Context;

interface PassFunctionCallInterface
{
    /**
     * @param FuncCall $funcCall
     * @param Context $context
     * @return mixed
     */
    public function pass(FuncCall $funcCall, Context $context);
}
