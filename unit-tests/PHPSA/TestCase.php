<?php

namespace Tests\PHPSA;

use PHPSA\Node\Scalar\Boolean;
use PHPSA\Visitor\Expression;
use RuntimeException;
use Symfony\Component\Console\Output\ConsoleOutput;
use PHPSA\Definition\ClassDefinition;
use PHPSA\Application;
use PhpParser\Node;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPSA\Context
     */
    protected function getContext()
    {
        $context = new \PHPSA\Context(new ConsoleOutput(), new Application());
        $context->setScope(new ClassDefinition('MathTest'));

        return $context;
    }

    /**
     * @param $expr
     * @return \PHPSA\CompiledExpression
     */
    protected function compileExpression($expr)
    {
        $visitor = new Expression($expr, $this->getContext());
        return $visitor->compile($expr);
    }

    /**
     * @param $value
     * @return Node\Scalar\DNumber|Node\Scalar\LNumber
     */
    public function newScalarExpr($value)
    {
        switch (gettype($value)) {
            case 'integer':
                return new Node\Scalar\LNumber($value);
                break;
            case 'double':
                return new Node\Scalar\DNumber($value);
                break;
            case 'boolean':
                return new Boolean($value);
                break;
            default:
                throw new RuntimeException('Unexpected type: ' . gettype($value));
                break;
        }
    }
}