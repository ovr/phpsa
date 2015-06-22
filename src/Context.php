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

    /**
     * @var Variable[]
     */
    protected $symbols = [];

    /**
     * @param $name
     * @return Variable
     */
    public function addSymbol($name)
    {
        $variable = new Variable($name);
        $this->symbols[$name] = $variable;

        return $variable;
    }

    /**
     * @param $name
     * @return Variable|null
     */
    public function getSymbol($name)
    {
        return isset($this->symbols[$name]) ? $this->symbols[$name] : null;
    }

    public function notice($type, $message, \PhpParser\NodeAbstract $expr)
    {
        $code = file($this->scope->getFilepath());

        $this->output->writeln('<comment>Notice:  ' . $message . " in {$this->scope->getFilepath()} on {$expr->getLine()} [{$type}]</comment>");
        $this->output->writeln('');

        $code = trim($code[$expr->getLine()-1]);
        $this->output->writeln("<comment>\t {$code} </comment>");
        $this->output->writeln('');

        unset($code);
    }

    public function sytaxError(\PhpParser\Error $e, $filepath)
    {
        $code = file($e->getFile());

        $this->output->writeln('<error>Syntax error:  ' . $e->getMessage() . " in {$filepath} </error>");
        $this->output->writeln('');
    }
}