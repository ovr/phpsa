<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPSA\Context;

class UseCast implements PassFunctionCallInterface
{
    protected $map = array(
        'boolval' => 'bool',
        'intval' => 'int',
        'floatval' => 'double',
        'doubleval' => 'double',
        'strval' => 'string'
    );

    public function visitPhpFunctionCall(FuncCall $funcCall, Context $context)
    {
        $name = false;

        if ($funcCall->name instanceof Name && !$funcCall->name->isFullyQualified()) {
            $name = $funcCall->name->getFirst();
        }

        if ($name && isset($this->map[$name])) {
            /**
             * Exclusion via intval with 2 args intval($number, int $base = 10);
             */
            if ($name == 'intval' && count($funcCall->args) > 1) {
                return;
            }

            $context->notice(
                'fcall.cast',
                sprintf('Please use (%s) cast instead of function call.', $this->map[$name]),
                $funcCall
            );
        }
    }
}
