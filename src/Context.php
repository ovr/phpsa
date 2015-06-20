<?php
/**
 * Created by PhpStorm.
 * User: ovr
 * Date: 20.06.15
 * Time: 16:28
 */

namespace PHPSA;

use PHPSA\Definition\ClassDefinition;
use Symfony\Component\Console\Output\OutputInterface;

class Context
{
    /**
     * @var ClassDefinition
     */
    public $scope;

    /**
     * @var Application
     */
    public $application;

    /**
     * @var OutputInterface
     */
    public $output;

    public function notice($type, $message, $expr)
    {
        $this->output->writeln('Notice:  ' . $message . " [{$type}]");
    }
}