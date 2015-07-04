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
     * Construct our Context with all needed information
     *
     * @param OutputInterface $output
     * @param Application $application
     */
    public function __construct(OutputInterface $output, Application $application)
    {
        $this->output = $output;
        $this->application = $application;
    }

    /**
     * @param string $name
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
        $this->scope = null;
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
        if ($name == 'this') {
            return new Variable($name, null, CompiledExpression::OBJECT);
        }

        return isset($this->symbols[$name]) ? $this->symbols[$name] : null;
    }

    /**
     * @param string $type
     * @param string $message
     * @return bool
     */
    public function warning($type, $message)
    {
        $this->output->writeln('<comment>Notice:  ' . $message . " in {$this->scope->getFilepath()}  [{$type}]</comment>");
        $this->output->writeln('');
        return true;
    }

    /**
     * @param string $type
     * @param string $message
     * @param \PhpParser\NodeAbstract $expr
     * @return bool
     */
    public function notice($type, $message, \PhpParser\NodeAbstract $expr)
    {
        $code = file($this->scope->getFilepath());

        $this->output->writeln('<comment>Notice:  ' . $message . " in {$this->scope->getFilepath()} on {$expr->getLine()} [{$type}]</comment>");
        $this->output->writeln('');

        if ($this->application->getConfiguration()->valueIsTrue('blame')) {
            exec("git blame --show-email -L {$expr->getLine()},{$expr->getLine()} " . $this->scope->getFilepath(), $result);
            if ($result && isset($result[0])) {
                $result[0] = trim($result[0]);

                $this->output->writeln("<comment>\t {$result[0]}</comment>");
            }
        } else {
            $code = trim($code[$expr->getLine()-1]);
            $this->output->writeln("<comment>\t {$code} </comment>");
        }

        $this->output->writeln('');

        unset($code);
        return true;
    }

    /**
     * @param \PhpParser\Error $exception
     * @param string $filepath
     * @return bool
     */
    public function sytaxError(\PhpParser\Error $exception, $filepath)
    {
        $code = file($filepath);

        $this->output->writeln('<error>Syntax error:  ' . $exception->getMessage() . " in {$filepath} </error>");
        $this->output->writeln('');

        $code = trim($code[($exception->getStartLine()-2)]);
        $this->output->writeln("<comment>\t {$code} </comment>");

        return true;
    }

    /**
     * @return Variable[]
     */
    public function getSymbols()
    {
        return $this->symbols;
    }

    /**
     * @param ClassDefinition $scope
     */
    public function setScope(ClassDefinition $scope)
    {
        $this->scope = $scope;
    }

    public function debug($message)
    {
        if ($this->output->isDebug()) {
            $this->output->writeln($message);
        }
    }
}
