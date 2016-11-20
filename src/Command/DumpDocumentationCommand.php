<?php

namespace PHPSA\Command;

use PHPSA\Analyzer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to dump the analyzer documentation as markdown
 */
class DumpDocumentationCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('config:dump-documentation')
            ->setDescription('Dumps the analyzer documentation')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $metaArray = Analyzer\Factory::getPassesMetadata();
        
        $output->writeln("# Analyzers");
        $output->writeln("This doc gives an overview about what the different analyzers do.");
        $output->writeln("");

        foreach ($metaArray as $analyzer) {
            $output->writeln("#### " . $analyzer->getName());
            $output->writeln("");
            $output->writeln($analyzer->getDescription());
            $output->writeln("");
        }

        $output->writeln("Next: [How To: Write own Analyzer](./06_HowTo_Own_Analyzer.md)");
    }
}
