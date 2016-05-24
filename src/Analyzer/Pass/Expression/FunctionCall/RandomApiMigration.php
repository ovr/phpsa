<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPSA\Compiler\Expression;
use PHPSA\Context;

class RandomApiMigration extends AbstractFunctionCallAnalyzer
{
    protected $map = array(
        'rand' => 'mt_rand',
        'srand' => 'mt_srand',
        'getrandmax' => 'mt_getrandmax'
    );

    public function pass(FuncCall $funcCall, Context $context)
    {
        $functionName = $this->resolveFunctionName($funcCall, $context);
        if ($functionName && isset($this->map[$functionName])) {
            $context->notice(
                'rand.api.migration',
                sprintf(
                    'Function %s() is not recommended, please use mt_%s analog instead of it.',
                    $functionName,
                    $functionName
                ),
                $funcCall
            );
        }
    }
}
