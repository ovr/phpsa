<?php

namespace Tests\PHPSA;

use PHPSA\CompiledExpression;
use PHPSA\Variable;

class ContextTest extends TestCase
{
    public function testModifyReferencedVariables()
    {
        $context = $this->getContext();

        /**
         * This variable is not needed for change
         */
        $variableAValue = 1;
        $variableAType = CompiledExpression::INTEGER;
        $context->addVariable(
            $variableA = new Variable('a', $variableAValue, $variableAType)
        );

        /**
         * $b = true;
         */
        $context->addVariable(
            $variableB = new Variable('b', null, CompiledExpression::BOOLEAN, true)
        );

        /**
         * $c = &$b;
         */
        $variableC = new Variable('c');
        $variableC->setReferencedTo($variableB);

        $context->addVariable(
            $variableC
        );

        $newType = CompiledExpression::INTEGER;
        $newValue = 55;

        /**
         * $b = {$newValue};
         * After it variable $c will change type and value
         */
        $context->modifyReferencedVariables($variableB, $newType, $newValue);

        self::assertSame($newValue, $variableC->getValue());
        self::assertSame($newType, $variableC->getType());

        /**
         * Assert that variable $a was not changed
         */
        self::assertSame($variableAValue, $variableA->getValue());
        self::assertSame($variableAType, $variableA->getType());
    }
}
