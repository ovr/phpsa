<?php

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PHPSA\Context;

class DeprecatedFunctions extends AbstractFunctionCallAnalyzer
{
    const DESCRIPTION = 'Checks for use of deprecated functions and gives alternatives if available.';

    protected $map = [
        'datefmt_set_timezone_id' => ['5.5','IntlDateFormatter::setTimeZone()'],
        'define_syslog_variables' => ['5.3','_'],
        'set_magic_quotes_runtime' => ['5.3','_'],
        'set_socket_blocking' => ['5.3','_'],
        'ereg' => ['5.3','preg_match()'],
        'eregi' => ['5.3','preg_match()'],
        'ereg_replace' => ['5.3','preg_replace()'],
        'eregi_replace' => ['5.3','preg_replace()'],
        'split' => ['5.3','explode()'],
        'spliti' => ['5.3','preg_split()'],
        'sql_regcase' => ['5.3','preg_match()'],
        'session_is_registered' => ['5.3','$_SESSION'],
        'session_unregister' => ['5.3','$_SESSION'],
        'session_register' => ['5.3','$_SESSION'],
    ];

    public function pass(FuncCall $funcCall, Context $context)
    {
        $functionName = $this->resolveFunctionName($funcCall, $context);
        if ($functionName) {
            if (isset($this->map[$functionName])) {
                $context->notice(
                    'deprecated.function',
                    sprintf('%s() is deprecated since PHP %s. Use %s instead.', $functionName, $this->map[$functionName][0], $this->map[$functionName][1]),
                    $funcCall
                );
            } elseif (substr($functionName, 0, 6) === 'mysql_') {
                $context->notice(
                    'deprecated.function',
                    sprintf('The MySQL Extension is deprecated since PHP 5.5. Use PDO instead.'),
                    $funcCall
                );
            } elseif (substr($functionName, 0, 7) === 'mcrypt_') {
                $context->notice(
                    'deprecated.function',
                    sprintf('The Mcrypt Extension is deprecated since PHP 7.1. Use paragonie/halite instead.'),
                    $funcCall
                );
            }
        }
    }
}
