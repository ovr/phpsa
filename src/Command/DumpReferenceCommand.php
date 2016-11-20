<?php
/**
 * @author Kévin Gomez https://github.com/K-Phoen <contact@kevingomez.fr>
 */

namespace PHPSA\Command;

use PHPSA\Analyzer;
use PHPSA\Configuration;
use Symfony\Component\Config\Definition\Dumper\YamlReferenceDumper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to dump the analyzer default configuration as YAML
 */
class DumpReferenceCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('config:dump-reference')
            ->setDescription('Dumps the default configuration')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $analyzerConfiguration = Analyzer\Factory::getPassesConfigurations();
        $configuration = new Configuration([], $analyzerConfiguration);
        $configTree = $configuration->getConfigTreeBuilder($analyzerConfiguration)->buildTree();

        $dumper = new YamlReferenceDumper();
        $output->writeln($dumper->dumpNode($configTree));
    }
}
