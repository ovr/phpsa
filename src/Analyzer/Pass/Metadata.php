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

    /** @var string|null */
    private $description;

    /** @var \Symfony\Component\Config\Definition\Builder\NodeDefinition */
    private $configuration;

    /** @var string|null */
    private $requiredPhpVersion;

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
     * @param NodeDefinition $config
     * @param string|null $description
     */
    public function __construct($name, NodeDefinition $config, $description = null)
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

    /**
     * @return string|null
     */
    public function getRequiredPhpVersion()
    {
        return $this->requiredPhpVersion;
    }

    /**
     * @param string $requiredPhpVersion
     */
    public function setRequiredPhpVersion($requiredPhpVersion)
    {
        $this->requiredPhpVersion = $requiredPhpVersion;
    }

    /**
     * Tells if the current analyzer can be used with code written in a given PHP version.
     *
     * @param string $version
     *
     * @return bool
     */
    public function allowsPhpVersion($version)
    {
        return $this->requiredPhpVersion === null || version_compare($version, $this->requiredPhpVersion, '>=');
    }
}
