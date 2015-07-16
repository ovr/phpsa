<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Definition;

use PHPSA\Context;
use PhpParser\Node;

/**
 * Class FunctionDefinition
 * @package PHPSA\Definition
 */
class FunctionDefinition extends AbstractDefinition
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @todo Use Finder
     *
     * @var string
     */
    protected $filepath;

    /**
     * @var Node\Stmt\Function_
     */
    protected $ast;

    /**
     * @param $name
     */
    public function __construct($name, Node\Stmt\Function_ $ast)
    {
        $this->name = $name;
        $this->ast = $ast;
    }

    /**
     * Compile function to check it
     *
     * @param Context $context
     * @return bool
     */
    public function compile(Context $context)
    {
        $context->setScope(null);

        if (count($this->ast->stmts) == 0) {
            return $context->notice(
                'not-implemented-method',
                sprintf('Method %s() is not implemented', $this->name),
                $this->ast
            );
        }

        if (count($this->ast->params) > 0) {
            /** @var  Node\Param $parameter */
            foreach ($this->ast->params as $parameter) {
                $context->addSymbol($parameter->name);
            }
        }

        foreach ($this->ast as $st) {
            \PHPSA\nodeVisitorFactory($st, $context);
        }

        return true;
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

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }
}
