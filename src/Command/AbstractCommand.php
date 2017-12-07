<?php

namespace PHPSA\Command;

use PhpParser\ParserFactory;
use PHPSA\Analyzer;
use PHPSA\Application;
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

        $loadedConfig = $loader->load($configFile);

        return new Configuration(
            $loadedConfig[0], // config
            Analyzer\Factory::getPassesConfigurations(),
            $loadedConfig[1] // path to config file
        );
    }

    /**
     * @param string $application
     *
     * @return PhpParser\Parser
     */
    protected function createParser($application)
    {
        $parserStr = $application->configuration->getValue('parser', 'prefer-7');
        switch ($parserStr) {
            case 'prefer-7':
                $languageLevel = ParserFactory::PREFER_PHP7;
                break;
            case 'prefer-5':
                $languageLevel = ParserFactory::PREFER_PHP5;
                break;
            case 'only-7':
                $languageLevel = ParserFactory::ONLY_PHP7;
                break;
            case 'only-5':
                $languageLevel = ParserFactory::ONLY_PHP5;
                break;
            default:
                $languageLevel = ParserFactory::PREFER_PHP7;
                break;
        }

        return (new ParserFactory())->create($languageLevel, new \PhpParser\Lexer\Emulative([
            'usedAttributes' => [
                'comments',
                'startLine',
                'endLine',
                'startTokenPos',
                'endTokenPos'
            ]
        ]));
    }
}
