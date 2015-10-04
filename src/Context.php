<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use PHPSA\Compiler\GlobalVariable;
use PHPSA\Definition\AbstractDefinition;
use PHPSA\Definition\ParentDefinition;
use Symfony\Component\Console\Output\OutputInterface;

class Context
{
    /**
     * For FunctionDefinition it's null, use scopePointer
     *
     * @var ParentDefinition|null
     */
    public $scope;

    /**
     * @var AliasManager
     */
    public $aliasManager;

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
     * @var ScopePointer|null
     */
    public $scopePointer;

    /**
     * @var string
     */
    protected $filepath;

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

        /**
         * http://php.net/manual/language.variables.superglobals.php
         */
        $this->addVariable(new GlobalVariable('GLOBALS', array(), CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_SERVER', array(), CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_GET', array(), CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_POST', array(), CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_FILES', array(), CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_COOKIE', array(), CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_SESSION', array(), CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_REQUEST', array(), CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_ENV', array(), CompiledExpression::ARR));
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
        return isset($this->symbols[$name]) ? $this->symbols[$name] : null;
    }

    /**
     * @param string $type
     * @param string $message
     * @return bool
     */
    public function warning($type, $message)
    {
        $filepath = $this->filepath;
        $this->output->writeln('<comment>Notice:  ' . $message . " in {$filepath}  [{$type}]</comment>");
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
        $filepath = $this->filepath;
        $code = file($filepath);

        $this->output->writeln('<comment>Notice:  ' . $message . " in {$filepath} on {$expr->getLine()} [{$type}]</comment>");
        $this->output->writeln('');

        if ($this->application->getConfiguration()->valueIsTrue('blame')) {
            exec("git blame --show-email -L {$expr->getLine()},{$expr->getLine()} " . $filepath, $result);
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
     * @param AbstractDefinition $scope
     */
    public function setScope(AbstractDefinition $scope = null)
    {
        $this->scope = $scope;
    }

    public function debug($message)
    {
        if ($this->output->isDebug()) {
            $this->output->writeln($message);
        }
    }

    /**
     * @return string
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * @param string $filepath
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
    }
}
