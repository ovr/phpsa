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
            $configs[] = $passClass::getMetadata()->getConfiguration();
        }

        foreach (self::getStatementPasses() as $passClass) {
            $configs[] = $passClass::getMetadata()->getConfiguration();
        }

        foreach (self::getScalarPasses() as $passClass) {
            $configs[] = $passClass::getMetadata()->getConfiguration();
        }

        return $configs;
    }

    /**
     * @return \PHPSA\Analyzer\Pass\Metadata[]
     */
    public static function getPassesMetadata()
    {
        $meta = [];

        foreach (self::getExpressionPasses() as $passClass) {
            $meta[] = $passClass::getMetadata();
        }

        foreach (self::getStatementPasses() as $passClass) {
            $meta[] = $passClass::getMetadata();
        }

        foreach (self::getScalarPasses() as $passClass) {
            $meta[] = $passClass::getMetadata();
        }

        return $meta;
    }

    /**
     * @param EventManager $eventManager
     * @param Configuration $config
     * @return Analyzer
     */
    public static function factory(EventManager $eventManager, Configuration $config)
    {
        $analyzersConfig = $config->getValue('analyzers');

        $filterEnabled = function ($passClass) use ($config, $analyzersConfig) {
            /** @var AnalyzerPass\Metadata $passMetadata */
            $passMetadata = $passClass::getMetadata();

            if (!isset($analyzersConfig[$passMetadata->getName()])) {
                return false;
            }

            if (!$analyzersConfig[$passMetadata->getName()]['enabled']) {
                return false;
            }

            if (!$passMetadata->allowsPhpVersion($config->getValue('language_level'))) {
                return false;
            }

            return true;
        };

        $instanciate = function ($passClass) use ($analyzersConfig) {
            $passName = $passClass::getMetadata()->getName();

            return new $passClass($analyzersConfig[$passName]);
        };

        $analyzer = new Analyzer($eventManager);
        $analyzer->registerExpressionPasses(
            array_map($instanciate, array_filter(self::getExpressionPasses(), $filterEnabled))
        );
        $analyzer->registerStatementPasses(
            array_map($instanciate, array_filter(self::getStatementPasses(), $filterEnabled))
        );
        $analyzer->registerScalarPasses(
            array_map($instanciate, array_filter(self::getScalarPasses(), $filterEnabled))
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
            AnalyzerPass\Statement\MissingBody::class,
            AnalyzerPass\Statement\InlineHtmlUsage::class,
            AnalyzerPass\Statement\AssignmentInCondition::class,
            AnalyzerPass\Statement\StaticUsage::class,
            AnalyzerPass\Statement\OptionalParamBeforeRequired::class,
            AnalyzerPass\Statement\YodaCondition::class,
            AnalyzerPass\Statement\ForCondition::class,
            AnalyzerPass\Statement\PropertyDefinitionDefaultValue::class,
            AnalyzerPass\Statement\ReturnAndYieldInOneMethod::class,
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
            AnalyzerPass\Expression\StupidUnaryOperators::class,
            AnalyzerPass\Expression\VariableVariableUsage::class,
            AnalyzerPass\Expression\Casts::class,
            AnalyzerPass\Expression\EvalUsage::class,
            AnalyzerPass\Expression\FinalStaticUsage::class,
            AnalyzerPass\Expression\CompareWithArray::class,
            AnalyzerPass\Expression\DivisionFromZero::class,
            AnalyzerPass\Expression\DivisionByOne::class,
            AnalyzerPass\Expression\BacktickUsage::class,
            AnalyzerPass\Expression\LogicInversion::class,
            AnalyzerPass\Expression\ExitUsage::class,
            AnalyzerPass\Expression\NestedTernary::class,
            // Arrays
            AnalyzerPass\Expression\ArrayShortDefinition::class,
            AnalyzerPass\Expression\ArrayDuplicateKeys::class,
            AnalyzerPass\Expression\ArrayIllegalOffsetType::class,
            // Function call
            AnalyzerPass\Expression\FunctionCall\AliasCheck::class,
            AnalyzerPass\Expression\FunctionCall\DebugCode::class,
            AnalyzerPass\Expression\FunctionCall\RandomApiMigration::class,
            AnalyzerPass\Expression\FunctionCall\SleepUsage::class,
            AnalyzerPass\Expression\FunctionCall\UseCast::class,
            AnalyzerPass\Expression\FunctionCall\DeprecatedIniOptions::class,
            AnalyzerPass\Expression\FunctionCall\RegularExpressions::class,
            AnalyzerPass\Expression\FunctionCall\ArgumentUnpacking::class,
            AnalyzerPass\Expression\FunctionCall\DeprecatedFunctions::class,
        ];
    }

    /**
     * @return array
     */
    private static function getScalarPasses()
    {
        return [
            AnalyzerPass\Scalar\CheckLNumberKind::class,
        ];
    }
}
