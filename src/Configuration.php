<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use PhpParser\ParserFactory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

class Configuration implements ConfigurationInterface, \ArrayAccess
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
    public function __construct(array $configuration = [], $analyzersConfiguration = [])
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
    public function getConfigTreeBuilder($analyzersConfiguration = [])
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('phpsa');

        $root
            ->children()
                ->booleanNode('blame')->defaultFalse()->end()
            ->end()
            ->children()
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
     * Checks if a configuration setting is set.
     *
     * @param string $key
     * @return bool
     */
    public function valueIsTrue($key)
    {
        return (bool) $this->configuration[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->configuration[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->setValue($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->configuration[$offset]);
    }
}
