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
        $context->addVariable(
            new Variable('a', null, CompiledExpression::INTEGER, 1)
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
    }
}
