<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPSA\Context;

class AliasCheck implements PassFunctionCallInterface
{
    protected $map = array(
        'join' => 'implode'
    );

    public function visitPhpFunctionCall(FuncCall $funcCall, Context $context)
    {
        $name = false;

        if ($funcCall->name instanceof Name && !$funcCall->name->isFullyQualified()) {
            $name = $funcCall->name->getFirst();
        }

        if ($name && isset($this->map[$name])) {
            $context->notice(
                'fcall.alias',
                sprintf('%s() is an alias of function. Use %s(...).', $name, $this->map[$name]),
                $funcCall
            );
        }
    }
}
