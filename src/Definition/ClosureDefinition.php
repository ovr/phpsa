<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PHPSA\CompiledExpression;
use PHPSA\Compiler\SymbolTable;
use PHPSA\Context;
use PhpParser\Node;
use PHPSA\Compiler\Parameter;
use PHPSA\Compiler\Types;

/**
 * Closure Definition
 *
 * @package PHPSA\Definition
 */
class ClosureDefinition extends ParentDefinition
{
    /**
     * @todo Use Finder
     *
     * @var string
     */
    protected $filepath;

    /**
     * @var Node\Expr\Closure
     */
    protected $statement;

    /**
     * @var int
     */
    protected $returnTypes = CompiledExpression::MIXED;

    /**
     * @var array
     */
    protected $possibleReturnTypes = [];

    /**
     * @var SymbolTable
     */
    protected $symbolTable;

    /**
     * @param Node\Expr\Closure $statement
     */
    public function __construct(Node\Expr\Closure $statement)
    {
        $this->symbolTable = new SymbolTable();
        $this->statement = $statement;
    }

    /**
     * @param Context $context
     */
    public function preCompile(Context $context)
    {
        if ($this->statement->uses) {
            /**
             * Store variables from User to next restore Context
             */
            foreach ($this->statement->uses as $variable) {
                $variable = $context->getSymbol($variable->var);
                if ($variable) {
                    $variable->incGets();

                    $this->symbolTable->add(clone $variable);
                }
            }
        }
    }

    /**
     * Compile function to check it
     *
     * @param Context $context
     * @return bool
     */
    public function compile(Context $context)
    {
        if ($this->compiled) {
            return true;
        }

        $context->setFilepath($this->filepath);
        $this->compiled = true;

        $context->clearSymbols();
        $context->scopePointer = $this->getPointer();
        $context->setScope(null);

        if (count($this->statement->stmts) == 0) {
            return $context->notice(
                'not-implemented-function',
                sprintf('Closure %s() is not implemented', $this->name),
                $this->statement
            );
        }

        if (count($this->statement->params) > 0) {
            /** @var  Node\Param $parameter */
            foreach ($this->statement->params as $parameter) {
                $type = CompiledExpression::UNKNOWN;

                if ($parameter->type) {
                    if (is_string($parameter->type)) {
                        $type = Types::getType($parameter->type);
                    } elseif ($parameter->type instanceof Node\Name) {
                        $type = CompiledExpression::OBJECT;
                    }
                }

                $context->addVariable(
                    new Parameter($parameter->name, null, $type, $parameter->byRef)
                );
            }
        }

        foreach ($this->statement->stmts as $st) {
            \PHPSA\nodeVisitorFactory($st, $context);
        }

        return true;
    }

    /**
     * @param CompiledExpression[] $arguments
     * @param Context $context
     * @return CompiledExpression
     */
    public function run(array $arguments, Context $context)
    {
        return new CompiledExpression();
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
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
}
