<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer;

use PHPSA\Analyzer;
use PHPSA\Configuration;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Webiny\Component\EventManager\EventManager;
use PHPSA\Analyzer\Pass as AnalyzerPass;

class Factory
{
    /**
     * @return NodeDefinition[]
     */
    public static function getPassesConfigurations()
    {
        $configs = [];

        foreach (self::getExpressionPasses() as $passClass) {
            if (!self::isPassConfigurable($passClass)) {
                continue;
            }

            $configs[] = $passClass::getConfiguration();
        }

        foreach (self::getStatementPasses() as $passClass) {
            if (!self::isPassConfigurable($passClass)) {
                continue;
            }

            $configs[] = $passClass::getConfiguration();
        }

        return $configs;
    }

    /**
     * @param EventManager $eventManager
     * @param Configuration $config
     * @return Analyzer
     */
    public static function factory(EventManager $eventManager, Configuration $config)
    {
        $filterEnabled = function($passClass) use ($config) {
            if (!self::isPassConfigurable($passClass)) {
                return true;
            }

            $passName = $passClass::getName();

            if (!isset($config['analyzers'][$passName], $config['analyzers'][$passName]['enabled'])) {
                return true;
            }

            return $config['analyzers'][$passName]['enabled'];
        };

        $instanciate = function($passClass) use ($config) {
            if (!self::isPassConfigurable($passClass)) {
                return new $passClass();
            }

            $passName = $passClass::getName();

            if (!isset($config['analyzers'][$passName])) {
                return new $passClass();
            }

            return new $passClass($config['analyzers'][$passName]);
        };

        $analyzer = new Analyzer($eventManager);
        $analyzer->registerExpressionPasses(
            array_map($instanciate, array_filter(self::getExpressionPasses(), $filterEnabled))
        );
        $analyzer->registerStatementPasses(
            array_map($instanciate, array_filter(self::getStatementPasses(), $filterEnabled))
        );
        $analyzer->bind();

        return $analyzer;
    }

    /**
     * @return array
     */
    private static function getStatementPasses()
    {
        return [
            AnalyzerPass\Statement\MagicMethodParameters::class,
            AnalyzerPass\Statement\GotoUsage::class,
            AnalyzerPass\Statement\GlobalUsage::class,
            AnalyzerPass\Statement\HasMoreThanOneProperty::class,
            AnalyzerPass\Statement\MissingBreakStatement::class,
            AnalyzerPass\Statement\MissingVisibility::class,
            AnalyzerPass\Statement\MethodCannotReturn::class,
            AnalyzerPass\Statement\UnexpectedUseOfThis::class,
            AnalyzerPass\Statement\TestAnnotation::class,
            AnalyzerPass\Statement\MissingDocblock::class,
            AnalyzerPass\Statement\OldConstructor::class,
            AnalyzerPass\Statement\ConstantNaming::class,
            AnalyzerPass\Statement\InlineHtmlUsage::class,
            AnalyzerPass\Statement\AssignmentInCondition::class,
            AnalyzerPass\Statement\StaticUsage::class,
            AnalyzerPass\Statement\OptionalParamBeforeRequired::class,
            AnalyzerPass\Statement\YodaCondition::class,
        ];
    }

    /**
     * @return array
     */
    private static function getExpressionPasses()
    {
        return [
            // Another
            AnalyzerPass\Expression\ErrorSuppression::class,
            AnalyzerPass\Expression\MultipleUnaryOperators::class,
            AnalyzerPass\Expression\VariableVariableUsage::class,
            AnalyzerPass\Expression\Casts::class,
            AnalyzerPass\Expression\EvalUsage::class,
            AnalyzerPass\Expression\FinalStaticUsage::class,
            AnalyzerPass\Expression\CompareWithArray::class,
            AnalyzerPass\Expression\BacktickUsage::class,
            AnalyzerPass\Expression\LogicInversion::class,
            AnalyzerPass\Expression\ExitUsage::class,
            // Arrays
            AnalyzerPass\Expression\ArrayShortDefinition::class,
            AnalyzerPass\Expression\ArrayDuplicateKeys::class,
            AnalyzerPass\Expression\ArrayIllegalOffsetType::class,
            // Function call
            AnalyzerPass\Expression\FunctionCall\AliasCheck::class,
            AnalyzerPass\Expression\FunctionCall\DebugCode::class,
            AnalyzerPass\Expression\FunctionCall\RandomApiMigration::class,
            AnalyzerPass\Expression\FunctionCall\UseCast::class,
            AnalyzerPass\Expression\FunctionCall\DeprecatedIniOptions::class,
            AnalyzerPass\Expression\FunctionCall\RegularExpressions::class,
            AnalyzerPass\Expression\FunctionCall\ArgumentUnpacking::class,
            AnalyzerPass\Expression\FunctionCall\DeprecatedFunctions::class,
        ];
    }

    private static function isPassConfigurable($passName)
    {
        return in_array(AnalyzerPass\ConfigurablePassInterface::class, class_implements($passName), true);
    }
}
