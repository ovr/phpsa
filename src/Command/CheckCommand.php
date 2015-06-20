<?php
/**
 * @author Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('check')
            ->setDescription('SPA')
            ->setDefinition(array(
                new InputArgument('path', InputArgument::REQUIRED),
            ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
