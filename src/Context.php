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
    protected $symbols = array();

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
     * @param Variable $variable
     * @return bool
     */
    public function addVariable(Variable $variable)
    {
        $this->symbols[$variable->getName()] = $variable;

        return true;
    }

    /**
     * Clear prevent context
     */
    public function clear()
    {
        $this->symbols = array();
        $this->scope = array();
    }

    public function clearSymbols()
    {
        unset($this->symbols);
        $this->symbols = array();
    }

    public function dump()
    {
        var_dump($this->symbols);
    }

    /**
     * @param $name
     * @return Variable|null
     */
    public function getSymbol($name)
    {
        return isset($this->symbols[$name]) ? $this->symbols[$name] : null;
    }

    /**
     * @param $type
     * @param $message
     * @return bool
     */
    public function warning($type, $message)
    {
        $this->output->writeln('<comment>Notice:  ' . $message . " in {$this->scope->getFilepath()}  [{$type}]</comment>");
        $this->output->writeln('');
        return true;
    }

    /**
     * @param $type
     * @param $message
     * @param \PhpParser\NodeAbstract $expr
     * @return bool
     */
    public function notice($type, $message, \PhpParser\NodeAbstract $expr)
    {
        $code = file($this->scope->getFilepath());

        $this->output->writeln('<comment>Notice:  ' . $message . " in {$this->scope->getFilepath()} on {$expr->getLine()} [{$type}]</comment>");
        $this->output->writeln('');

        $code = trim($code[$expr->getLine()-1]);
        $this->output->writeln("<comment>\t {$code} </comment>");
        $this->output->writeln('');

        unset($code);
        return true;
    }

    /**
     * @param \PhpParser\Error $e
     * @param $filepath
     * @return bool
     */
    public function sytaxError(\PhpParser\Error $e, $filepath)
    {
        $code = file($e->getFile());

        $this->output->writeln('<error>Syntax error:  ' . $e->getMessage() . " in {$filepath} </error>");
        $this->output->writeln('');
        return true;
    }

    /**
     * @return Variable[]
     */
    public function getSymbols()
    {
        return $this->symbols;
    }
}