<?php

namespace Tests\PHPSA;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Node\Scalar\Boolean;
use PHPSA\Node\Scalar\Fake;
use PHPSA\Node\Scalar\Nil;
use PHPSA\Compiler\Expression;
use RuntimeException;
use Symfony\Component\Console\Output\ConsoleOutput;
use PHPSA\Definition\ClassDefinition;
use PHPSA\Application;
use PhpParser\Node;
use Webiny\Component\EventManager\EventManager;

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
                new ConsoleOutput(), new Application(), EventManager::getInstance()
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
        parent::assertInstanceOf(
            \PHPSA\CompiledExpression::class,
            $actual,
            $message
        );
    }

    /**
     * @param object $expr
     * @param Context|null $expr
     * @return \PHPSA\CompiledExpression
     */
    protected function compileExpression($expr, Context $context = null)
    {
        if (!$context) {
            $context = $this->getContext();
        }

        return $context->getExpressionCompiler()->compile($expr);
    }

    /**
     * @param int $type
     * @param null $value
     * @return Fake
     */
    public function newFakeScalarExpr($type = CompiledExpression::UNKNOWN, $value = null)
    {
        return new Fake($value, $type);
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
