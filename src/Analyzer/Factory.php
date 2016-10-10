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
                new AnalyzerPass\Expression\ErrorSuppression(),
                new AnalyzerPass\Expression\MultipleUnaryOperators(),
                new AnalyzerPass\Expression\VariableVariableUsage(),
                new AnalyzerPass\Expression\Casts(),
                new AnalyzerPass\Expression\EvalUsage(),
                new AnalyzerPass\Expression\FinalStaticUsage(),
                new AnalyzerPass\Expression\CompareWithArray(),
                new AnalyzerPass\Expression\BacktickUsage(),
                new AnalyzerPass\Expression\LogicInversion(),
                // Arrays
                new AnalyzerPass\Expression\ArrayShortDefinition(),
                new AnalyzerPass\Expression\ArrayDuplicateKeys(),
                new AnalyzerPass\Expression\ArrayIllegalOffsetType(),
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
                new AnalyzerPass\Statement\MagicMethodParameters(),
                new AnalyzerPass\Statement\GotoUsage(),
                new AnalyzerPass\Statement\GlobalUsage(),
                new AnalyzerPass\Statement\HasMoreThanOneProperty(),
                new AnalyzerPass\Statement\MissingBreakStatement(),
                new AnalyzerPass\Statement\MissingVisibility(),
                new AnalyzerPass\Statement\MethodCannotReturn(),
                new AnalyzerPass\Statement\UnexpectedUseOfThis(),
                new AnalyzerPass\Statement\TestAnnotation(),
                new AnalyzerPass\Statement\MissingDocblock(),
                new AnalyzerPass\Statement\OldConstructor(),
                new AnalyzerPass\Statement\ConstantNaming(),
                new AnalyzerPass\Statement\InlineHtmlUsage(),
                new AnalyzerPass\Statement\AssignmentInCondition(),
                new AnalyzerPass\Statement\StaticUsage(),
                new AnalyzerPass\Statement\OptionalParamBeforeRequired(),
            ]
        );
        $analyzer->bind();

        return $analyzer;
    }
}
