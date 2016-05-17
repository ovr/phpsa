<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPSA\Compiler\Expression;
use PHPSA\Context;

class RandomApiMigration implements PassFunctionCallInterface
{
    protected $map = array(
        'rand' => 'mt_rand',
        'srand' => 'mt_srand',
        'getrandmax' => 'mt_getrandmax'
    );

    public function pass(FuncCall $funcCall, Context $context)
    {
        $compiler = $context->getExpressionCompiler();
        $funcNameCompiledExpression = $compiler->compile($funcCall->name);

        if ($funcNameCompiledExpression->isString() && $funcNameCompiledExpression->isCorrectValue()) {
            $name = $funcNameCompiledExpression->getValue();
        } else {
            $context->debug(
                'Unexpected function name type ' . $funcNameCompiledExpression->getType(),
                $funcCall->name
            );

            return false;
        }

        if (isset($this->map[$name])) {
            $context->notice(
                'rand.api.migration',
                sprintf('Function %s() is not recommended, please use mt_%s analog instead of it.', $name, $name),
                $funcCall
            );
        }
    }
}
