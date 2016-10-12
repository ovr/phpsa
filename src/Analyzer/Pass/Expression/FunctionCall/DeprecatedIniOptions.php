<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Expression\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PHPSA\Context;

class DeprecatedIniOptions extends AbstractFunctionCallAnalyzer
{
    const DESCRIPTION = 'Checks for use of deprecated php.ini options and gives alternatives if available.';

    static protected $functions = [
        'ini_set' => 'ini_set',
        'ini_get' => 'ini_get',
        'ini_alter' => 'ini_alter',
        'ini_restore' => 'ini_restore'
    ];

    static protected $deprecatedOptions = [
        'asp_tags' => 'is a deprecated option since PHP 7.0.0',
        'always_populate_raw_post_data' => 'is a deprecated option since PHP 7.0.0',
        //
        'iconv.input_encoding' => 'is a deprecated option since PHP 5.6.0. Use \'default_charset\' instead',
        'iconv.output_encoding' => 'is a deprecated option since PHP 5.6.0. Use \'default_charset\' instead',
        'iconv.internal_encoding' => 'is a deprecated option since PHP 5.6.0. Use \'default_charset\' instead',
        'mbstring.http_input' => 'is a deprecated option since PHP 5.6.0. Use \'default_charset\' instead',
        'mbstring.http_output' => 'is a deprecated option since PHP 5.6.0. Use \'default_charset\' instead',
        'mbstring.internal_encoding' => 'is a deprecated option since PHP 5.6.0. Use \'default_charset\' instead',
        //
        'xsl.security_prefs' => 'is a deprecated option since PHP 5.4.0 (removed in PHP 7.0.0). Use XsltProcessor->setSecurityPrefs() instead',
        //
        'allow_call_time_pass_reference' => 'is a deprecated option since PHP 5.4.0',
        'highlight.bg' => 'is a deprecated option since PHP 5.4.0',
        'zend.ze1_compatibility_mode' => 'is a deprecated option since PHP 5.4.0',
        'session.bug_compat_42' => 'is a deprecated option since PHP 5.4.0',
        'session.bug_compat_warn' => 'is a deprecated option since PHP 5.4.0',
        'y2k_compliance' => 'is a deprecated option since PHP 5.4.0',
        //
        'define_syslog_variables' => 'is a deprecated option since PHP 5.3.0 (removed in PHP 5.4.0)',
        'magic_quotes_gpc' => 'is a deprecated option since PHP 5.3.0 (removed in PHP 5.4.0)',
        'magic_quotes_runtime' => 'is a deprecated option since PHP 5.3.0 (removed in PHP 5.4.0)',
        'magic_quotes_sybase' => 'is a deprecated option since PHP 5.3.0 (removed in PHP 5.4.0)',
        'register_globals' => 'is a deprecated option since PHP 5.3.0 (removed in PHP 5.4.0)',
        'register_long_arrays' => 'is a deprecated option since PHP 5.3.0 (removed in PHP 5.4.0)',
        'safe_mode' => 'is a deprecated option since PHP 5.3.0 (removed in PHP 5.4.0)',
        'safe_mode_gid' => 'is a deprecated option since PHP 5.3.0 (removed in PHP 5.4.0)',
        'safe_mode_include_dir' => 'is a deprecated option since PHP 5.3.0 (removed in PHP 5.4.0)',
        'safe_mode_exec_dir' => 'is a deprecated option since PHP 5.3.0 (removed in PHP 5.4.0)',
        'safe_mode_allowed_env_vars' => 'is a deprecated option since PHP 5.3.0 (removed in PHP 5.4.0)',
        'safe_mode_protected_env_vars' => 'is a deprecated option since PHP 5.3.0 (removed in PHP 5.4.0)'
    ];

    public function pass(FuncCall $funcCall, Context $context)
    {
        $functionName = $this->resolveFunctionName($funcCall, $context);
        if ($functionName && isset(self::$functions[$functionName])) {
            if ($funcCall->args) {
                $compiledOptionName = $context->getExpressionCompiler()->compile($funcCall->args[0]);
                if ($compiledOptionName->isString() && $compiledOptionName->isCorrectValue()) {
                    if (isset(self::$deprecatedOptions[$compiledOptionName->getValue()])) {
                        $context->notice(
                            'deprecated.option',
                            sprintf(
                                'Ini option %s %s.',
                                $compiledOptionName->getValue(),
                                self::$deprecatedOptions[$compiledOptionName->getValue()]
                            ),
                            $funcCall
                        );
                    }
                }
            }
        }
    }
}
