<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use PhpParser\ParserFactory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

/**
 * PHPSA configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var array
     */
    protected $configuration;

    /**
     * Create a configuration from array.
     *
     * @param array $configuration
     * @param array $analyzersConfiguration
     */
    public function __construct(array $configuration = [], array $analyzersConfiguration = [])
    {
        $processor = new Processor();

        $configTree = $this->getConfigTreeBuilder($analyzersConfiguration);

        $this->configuration = $processor->process(
            $configTree->buildTree(),
            $configuration
        );
    }

    /**
     * Generates the configuration tree.
     *
     * @param array $analyzersConfiguration
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(array $analyzersConfiguration = [])
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('phpsa');

        $root
            ->children()
                ->booleanNode('blame')->defaultFalse()->end()
                ->scalarNode('language_level')
                    ->defaultValue(PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION)
                    ->attribute('example', '5.3')
                    ->attribute('info', 'Will be used to automatically disable the analyzers that require a greater version of PHP.')
                ->end()
                ->enumNode('parser')
                    ->defaultValue('prefer-7')
                    ->attribute('label', 'Check types of Arguments.')
                    ->values([
                        ParserFactory::PREFER_PHP7 => 'prefer-7',
                        ParserFactory::PREFER_PHP5 => 'prefer-5',
                        ParserFactory::ONLY_PHP7 => 'only-7',
                        ParserFactory::ONLY_PHP5 => 'only-5'
                    ])
                ->end()
            ->end()
        ;

        $analyzersConfigRoot = $root
            ->children()
                ->arrayNode('analyzers')
                ->addDefaultsIfNotSet();

        foreach ($analyzersConfiguration as $config) {
            $analyzersConfigRoot->append($config);
        }

        return $treeBuilder;
    }

    /**
     * Sets a configuration setting.
     *
     * @param string $key
     * @param string $value
     */
    public function setValue($key, $value)
    {
        $this->configuration[$key] = $value;
    }

    /**
     * Gets a configuration setting.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function getValue($key, $default = null)
    {
        if (array_key_exists($key, $this->configuration)) {
            return $this->configuration[$key];
        }

        return $default;
    }

    /**
     * Checks if a configuration setting is set.
     *
     * @param string $key
     * @return bool
     */
    public function valueIsTrue($key)
    {
        return (bool) $this->configuration[$key];
    }
}
