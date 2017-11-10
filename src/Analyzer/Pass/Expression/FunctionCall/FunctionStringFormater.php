<?php

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PHPSA\Context;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PHPSA\Analyzer\Pass;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;

class FunctionStringFormater extends AbstractFunctionCallAnalyzer
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Format string has same number of placeholders as parameters are passed into and forbid invalid type formats.';

    /**
     * @var array functions
     */
    protected static $functions = [
        'printf' => 'printf',
        'sprintf' => 'sprintf',
        'vprintf' => 'vprintf',
        'vsprintf' => 'vsprintf'
    ];
    /**
     * Placeholders for type format
     * @var array
     */
    protected $placeholders = [];

    /**
     * @param FuncCall $funcCall
     * @param Context $context
     * @return bool
     */
    public function pass(FuncCall $funcCall, Context $context)
    {
        $functionName = $this->resolveFunctionName($funcCall, $context);
        if ($functionName && isset(self::$functions[$functionName])) {
            if ($funcCall->args) {
                $args = $funcCall->args;

                if (! ($args[0]->value instanceof String_)) {
                    $context->notice(
                        'function_argument_invalid',
                        sprintf('First parameter of %s must be string', $functionName),
                        $funcCall
                    );
                }

                if (($args[0]->value instanceof String_)) {
                    $string = $args[0]->value->value;
                    // get invalid placeholders
                    preg_match_all("/(?<!\x25)\x25(?:([1-9]\d*)\$|\(([^\)]+)\))?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?[^bcdeEufFgGosxX(%)]/", $string, $this->placeholders);
                    if (count($this->placeholders[0]) > 0) {
                        $context->notice(
                            'function_format_type_invalid',
                            sprintf('Unexpected type format in %s function string', $functionName),
                            $funcCall
                        );
                    } else {
                        // get valid placesholders
                        preg_match_all("/(?<!\x25)\x25(?:([1-9]\d*)\$|\(([^\)]+)\))?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([bcdeEufFgGosxX])/", $string, $this->placeholders);
                        if ($args[1]->value instanceof Array_) {
                            if (count($this->placeholders[0]) !== count($args[1]->value->items)) {
                                $context->notice(
                                    'function_array_length_invalid',
                                    sprintf('Unexpected length of array passed to %s', $functionName),
                                    $funcCall
                                );
                            }
                        } else {
                            if (count($this->placeholders[0]) !== (count($args) - 1)) {
                                $context->notice(
                                    'function_arguments_length_invalid',
                                    sprintf('Unexpected length of arguments passed to %s', $functionName),
                                    $funcCall
                                );
                            }
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            FuncCall::class
        ];
    }
}
