<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */


namespace PHPSA;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class ConfigurationLoader extends FileLoader
{
    public function load($resource, $type = null)
    {
        try {
            $config = $this->locator->locate($resource);
        } catch (\InvalidArgumentException $e) {
            return [];
        }

        return Yaml::parse(file_get_contents($resource));
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
