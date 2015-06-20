<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
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

    public function notice($type, $message, \PhpParser\NodeAbstract $expr)
    {
        $code = file($this->scope->getFilepath());

        $this->output->writeln('<comment>Notice:  ' . $message . "  in  tests/simple/undefined-property/1.php on {$expr->getLine()} [{$type}]</comment>");
        $this->output->writeln('');

        $code = trim($code[$expr->getLine()-1]);
        $this->output->writeln("<comment>\t {$code} </comment>");
        $this->output->writeln('');
    }
}