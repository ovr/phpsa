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
    protected $symbols = [];

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

    /**
     * Adds all global variables to the context.
     */
    public function initGlobals()
    {
        /**
         * http://php.net/manual/language.variables.superglobals.php
         */
        $this->addVariable(new GlobalVariable('GLOBALS', [], CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_SERVER', [], CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_GET', [], CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_POST', [], CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_FILES', [], CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_COOKIE', [], CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_SESSION', [], CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_REQUEST', [], CompiledExpression::ARR));
        $this->addVariable(new GlobalVariable('_ENV', [], CompiledExpression::ARR));
    }

    /**
     * Creates a variable from a symbol and adds it to the context.
     *
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
     * Adds a variable to the context.
     *
     * @param Variable $variable
     * @return bool
     */
    public function addVariable(Variable $variable)
    {
        $this->symbols[$variable->getName()] = $variable;

        return true;
    }

    /**
     * Resets context to beginning stage.
     */
    public function clear()
    {
        $this->symbols = [];
        $this->scope = null;
        $this->scopePointer = null;
        $this->currentBranch = null;
        $this->aliasManager = null;

        $this->initGlobals();
    }

    /**
     * Clears only all symbols.
     */
    public function clearSymbols()
    {
        unset($this->symbols);
        $this->symbols = [];
    }

    /**
     * Returns a variable if it exists.
     *
     * @param $name
     * @return Variable|null
     */
    public function getSymbol($name)
    {
        return isset($this->symbols[$name]) ? $this->symbols[$name] : null;
    }

    /**
     * Creates a warning message.
     *
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
     * Creates a notice message.
     *
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

        $issueCollector = $this->application->getIssuesCollector();
        $issueCollector->addIssue(
            new Issue(
                $type,
                $message,
                new IssueLocation(
                    $this->filepath,
                    $expr->getLine() - 1
                )
            )
        );

        return true;
    }

    /**
     * Creates a syntax error message.
     *
     * @param \PhpParser\Error $exception
     * @param string $filepath
     * @return bool
     */
    public function syntaxError(\PhpParser\Error $exception, $filepath)
    {
        $code = file($filepath);

        $this->output->writeln('<error>Syntax error:  ' . $exception->getMessage() . " in {$filepath} </error>");
        $this->output->writeln('');

        $issueCollector = $this->application->getIssuesCollector();
        $issueCollector->addIssue(
            new Issue(
                'syntax-error',
                'syntax-error',
                new IssueLocation(
                    $filepath,
                    $exception->getStartLine() - 2
                )
            )
        );

        $code = trim($code[$exception->getStartLine()-2]);
        $this->output->writeln("<comment>\t {$code} </comment>");

        return true;
    }

    /**
     * Returns an array of all variables.
     *
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

    /**
     * Creates a debug message.
     */
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
     * Updates all references on the given variable.
     *
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
