<?php

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr\Cast;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;
use PHPSA\CompiledExpression;

class Casts implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for casts that try to cast a type to itself.';

    /**
     * @param Cast $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Cast $expr, Context $context)
    {
        $castType = CompiledExpression::UNKNOWN;

        switch (get_class($expr)) {
            case Cast\Array_::class:
                $castType = CompiledExpression::ARR;
                break;
            case Cast\Bool_::class:
                $castType = CompiledExpression::BOOLEAN;
                break;
            case Cast\Int_::class:
                $castType = CompiledExpression::INTEGER;
                break;
            case Cast\Double::class:
                $castType = CompiledExpression::DOUBLE;
                break;
            case Cast\Object_::class:
                $castType = CompiledExpression::OBJECT;
                break;
            case Cast\String_::class:
                $castType = CompiledExpression::STRING;
                break;
        }

        $compiledExpression = $context->getExpressionCompiler()->compile($expr->expr);
        $exprType = $compiledExpression->getType();
        $typeName = $compiledExpression->getTypeName();

        if ($castType === $exprType) {
            $context->notice(
                'stupid.cast',
                sprintf("You are trying to cast '%s' to '%s'", $typeName, $typeName),
                $expr
            );
            return true;
        } elseif (get_class($expr) == Cast\Unset_::class && $exprType === CompiledExpression::NULL) {
            $context->notice(
                'stupid.cast',
                "You are trying to cast 'null' to 'unset' (null)",
                $expr
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
            Cast\Array_::class,
            Cast\Bool_::class,
            Cast\Int_::class,
            Cast\Double::class,
            Cast\Object_::class,
            Cast\String_::class,
            Cast\Unset_::class,
        ];
    }
}
