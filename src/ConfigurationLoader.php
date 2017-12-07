<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * Loads configuration from a YAML file
 */
class ConfigurationLoader extends FileLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        try {
            $path = $this->locator->locate($resource);
            return [Yaml::parse(file_get_contents($path)), $path];
        } catch (\InvalidArgumentException $e) {
            return [];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
