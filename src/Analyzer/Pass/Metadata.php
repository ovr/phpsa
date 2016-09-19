<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Analyzer\Pass;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Describes an analyzer pass.
 */
class Metadata
{
    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var \Symfony\Component\Config\Definition\Builder\NodeDefinition */
    private $configuration;

    /**
     * @param string $name
     * @param string|null $description
     *
     * @return Metadata
     */
    public static function create($name, $description = null)
    {
        $treeBuilder = new TreeBuilder();

        $config = $treeBuilder->root($name)
            ->info($description)
            ->canBeDisabled()
        ;

        return new self($name, $config, $description);
    }

    /**
     * @param string $name
     * @param null $description
     * @param NodeDefinition|null $config
     */
    private function __construct($name, NodeDefinition $config, $description = null)
    {
        $this->name = $name;
        $this->configuration = $config;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return NodeDefinition
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}
