<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\Return_;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass;
use PHPSA\CompiledExpression;
use PHPSA\Context;

class ReturnVoid implements Pass\AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for return void statements.';

    /**
     * @param $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(Return_ $stmt, Context $context)
    {
        // this is not the value null but "no value"
        if ($stmt->expr === null) {
            $scopePointer = $context->scopePointer;
            if ($scopePointer && $scopePointer->isClassMethod()) {
                /** @var \PHPSA\Definition\ClassMethod $method */
                $method = $scopePointer->getObject();
                if ($method->getReturnType() == CompiledExpression::VOID) {
                    return false;
                }
            }


            $context->notice(
                'return.void',
                'You are trying to return void',
                $stmt
            );

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Return_::class,
        ];
    }
}
