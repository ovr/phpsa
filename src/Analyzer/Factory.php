<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer;

use PHPSA\Analyzer;
use Webiny\Component\EventManager\EventManager;
use PHPSA\Analyzer\Pass as AnalyzerPass;

class Factory
{
    /**
     * @param EventManager $eventManager
     * @return Analyzer
     */
    public static function factory(EventManager $eventManager)
    {
        $analyzer = new Analyzer($eventManager);
        $analyzer->registerExpressionPasses(
            [
                // Another
                new AnalyzerPass\Expression\ArrayShortDefinition(),
                new AnalyzerPass\Expression\ErrorSuppression(),
                new AnalyzerPass\Expression\VariableVariableUsage(),
                new AnalyzerPass\Expression\Casts(),
                // Function call
                new AnalyzerPass\Expression\FunctionCall\AliasCheck(),
                new AnalyzerPass\Expression\FunctionCall\DebugCode(),
                new AnalyzerPass\Expression\FunctionCall\RandomApiMigration(),
                new AnalyzerPass\Expression\FunctionCall\UseCast(),
                new AnalyzerPass\Expression\FunctionCall\DeprecatedIniOptions(),
                new AnalyzerPass\Expression\FunctionCall\RegularExpressions(),
                new AnalyzerPass\Expression\FunctionCall\ArgumentUnpacking(),
                new AnalyzerPass\Expression\FunctionCall\DeprecatedFunctions(),
            ]
        );
        $analyzer->registerStatementPasses(
            [
                new AnalyzerPass\Statement\DoNotUseGoto(),
                new AnalyzerPass\Statement\MissingBreakStatement(),
                new AnalyzerPass\Statement\MethodCannotReturn(),
                new AnalyzerPass\Statement\UnexpectedUseOfThis(),
                new AnalyzerPass\Statement\OldConstructor(),
            ]
        );
        $analyzer->bind();

        return $analyzer;
    }
}
