<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */


namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PHPSA\Check;
use PHPSA\Context;

class RegularExpressions extends AbstractFunctionCallAnalyzer
{
    const DESCRIPTION = 'Checks that regular expressions are syntactically correct.';

    static public $map = [
        'preg_filter' => 0,
        'preg_grep' => 0,
        'preg_match_all' => 0,
        'preg_match' => 0,
        'preg_quote' => 0,
//        'preg_replace_callback_array' => 0,
        'preg_replace_callback' => 0,
        'preg_replace' => 0,
        'preg_split' => 0,
    ];

    /**
     * @param FuncCall $funcCall
     * @param Context $context
     * @return mixed
     */
    public function pass(FuncCall $funcCall, Context $context)
    {
        $functionName = $this->resolveFunctionName($funcCall, $context);
        if ($functionName && isset(self::$map[$functionName])) {
            $pattern = $context->getExpressionCompiler()->compile($funcCall->args[0]);
            if ($pattern->isString() && $pattern->isCorrectValue()) {
                $guard = \RegexGuard\Factory::getGuard();
                if (!$guard->isRegexValid($pattern->getValue())) {
                    $context->notice(
                        'regex.invalid',
                        sprintf(
                            'Regular expression %s is not valid',
                            $pattern->getValue()
                        ),
                        $funcCall->args[0],
                        Check::CHECK_ALPHA
                    );
                }
            }
        }
    }
}
