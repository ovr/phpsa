<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use PhpParser\ParserFactory;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

class Configuration implements ConfigurationInterface
{
    /**
     * @var array
     */
    protected $configuration;

    public function __construct(array $configuration = [])
    {
        $processor = new Processor();
        $treeBuilder = $this->getConfigTreeBuilder();

        $this->configuration = $processor->process(
            $treeBuilder->buildTree(),
            $configuration
        );
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('common', 'array', new NodeBuilder())
            ->children()
                ->booleanNode('blame')
                ->defaultFalse()
                ->end()
            ->end()
            ->children()
                ->enumNode('parser')
                    ->defaultValue('prefer-7')
                    ->attribute('label', 'Check types of Arguments.')
                    ->values(
                        array(
                            ParserFactory::PREFER_PHP7 => 'prefer-7',
                            ParserFactory::PREFER_PHP5 => 'prefer-5',
                            ParserFactory::ONLY_PHP7 => 'only-7',
                            ParserFactory::ONLY_PHP5 => 'only-5'
                        )
                    );


        return $treeBuilder;
    }

    public function setValue($key, $value)
    {
        $this->configuration[$key] = $value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function valueIsTrue($key)
    {
        return (bool) $this->configuration[$key];
    }
}
