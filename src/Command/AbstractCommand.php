<?php

namespace PHPSA\Command;

use PHPSA\Analyzer;
use PHPSA\Configuration;
use PHPSA\ConfigurationLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Config\FileLocator;

/**
 * Base Command providing config loading, memory usage
 */
abstract class AbstractCommand extends Command
{

    /**
     * @param boolean $type
     * @return float
     */
    protected function getMemoryUsage($type)
    {
        return round(memory_get_usage($type) / 1024 / 1024, 2);
    }

    /**
     * @param string $configFile
     * @param string $configurationDirectory
     *
     * @return Configuration
     */
    protected function loadConfiguration($configFile, $configurationDirectory)
    {
        $loader = new ConfigurationLoader(new FileLocator([
            getcwd(),
            $configurationDirectory
        ]));

        return new Configuration(
            $loader->load($configFile),
            Analyzer\Factory::getPassesConfigurations()
        );
    }
}
