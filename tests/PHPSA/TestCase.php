<?php

namespace Tests\PHPSA;

use PHPSA\Node\Scalar\Boolean;
use PHPSA\Node\Scalar\Nil;
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
        /** @var \PHPSA\Context $context */
        $context = $this->getMock(
            '\PHPSA\Context',
            array(
                'notice'
            ),
            array(
                new ConsoleOutput(), new Application()
            )
        );
        $context->setScope(new ClassDefinition('MathTest', 0));

        return $context;
    }

    /**
     * @param $actual
     * @param string $message
     */
    protected function assertInstanceOfCompiledExpression($actual, $message = '')
    {
        $this->assertInstanceOf('PHPSA\CompiledExpression', $actual, $message);
    }

    /**
     * @param $expr
     * @return \PHPSA\CompiledExpression
     */
    protected function compileExpression($expr)
    {
        $visitor = new Expression($this->getContext());
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
            case 'double':
                return new Node\Scalar\DNumber($value);
            case 'string':
                return new Node\Scalar\String_($value);
            case 'boolean':
                return new Boolean($value);
            case 'NULL':
                return new Nil();
            case 'array':
                if ($value === array()) {
                    return new Node\Expr\Array_(array());
                }

                throw new RuntimeException('newScalarExpr is not working with non-empty arrays');
        }

        throw new RuntimeException('Unexpected type: ' . gettype($value));
    }
}
