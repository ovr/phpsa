<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use PHPSA\Compiler\Expression;
use PHPSA\Compiler\GlobalVariable;
use PHPSA\Definition\AbstractDefinition;
use PHPSA\Definition\ParentDefinition;
use Symfony\Component\Console\Output\OutputInterface;
use Webiny\Component\EventManager\EventManager;

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
     * @var string|integer
     */
    public $currentBranch;

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
     * @var EventManager
     */
    protected $eventManager;

    /**
     * Construct our Context with all needed information
     *
     * @param OutputInterface $output
     * @param Application $application
     * @param EventManager $eventManager
     */
    public function __construct(OutputInterface $output, Application $application, EventManager $eventManager)
    {
        $this->output = $output;
        $this->application = $application;

        $this->initGlobals();
        $this->eventManager = $eventManager;
    }

    /**
     * @return Expression
     */
    public function getExpressionCompiler()
    {
        return new Expression($this, $this->eventManager);
    }

    public function initGlobals()
    {
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
        $this->scopePointer = null;
        $this->currentBranch = null;
        $this->aliasManager = null;

        $this->initGlobals();
    }

    public function clearSymbols()
    {
        unset($this->symbols);
        $this->symbols = array();
    }

    public function dump()
    {
        /**
         * @expected
         */
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
        $this->output
            ->writeln('<comment>Warning:  ' . $message . ' in ' . $this->filepath . '  [' . $type . ']</comment>');

        $this->output->writeln('');

        return true;
    }

    /**
     * @param string $type
     * @param string $message
     * @param \PhpParser\NodeAbstract $expr
     * @param int $status
     * @return bool
     */
    public function notice($type, $message, \PhpParser\NodeAbstract $expr, $status = Check::CHECK_SAFE)
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
            $code = trim($code[$expr->getLine() - 1]);
            $this->output->writeln("<comment>\t {$code} </comment>");
        }

        $this->output->writeln('');

        $this->application->getIssuesCollector()
            ->addIssue($type, $message, basename($this->filepath), $expr->getLine() - 1);

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

        $this->application->getIssuesCollector()
            ->addIssue('syntax-error', 'syntax-error', $filepath, $exception->getStartLine() - 2);

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

    public function debug($message, \PhpParser\Node $expr = null)
    {
        if ($this->output->isDebug()) {
            $this->output->writeln('[DEBUG] ' . $message);
            $this->output->write($this->filepath);

            if ($expr) {
                $this->output->write(':' . $expr->getLine());
            }

            $this->output->writeln('');
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

    /**
     * @return integer
     */
    public function getCurrentBranch()
    {
        return $this->currentBranch;
    }

    /**
     * @param int|string $currentBranch
     */
    public function setCurrentBranch($currentBranch)
    {
        $this->currentBranch = $currentBranch;
    }

    /**
     * @param Variable $variable
     * @param $type
     * @param $value
     */
    public function modifyReferencedVariables(Variable $variable, $type, $value)
    {
        foreach ($this->symbols as $symbol) {
            $referencedTo = $symbol->getReferencedTo();
            if ($referencedTo) {
                if ($referencedTo === $variable) {
                    $symbol->modify($type, $value);
                }
            }
        }
    }

    /**
     * @return EventManager
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }
}
