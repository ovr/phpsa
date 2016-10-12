<?php

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;
use PHPSA\CompiledExpression;

class Casts implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for casts that try to cast a type to itself.';

    /**
     * @param Expr $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr $expr, Context $context)
    {
        $castType = CompiledExpression::UNKNOWN;

        switch (get_class($expr)) {
            case Expr\Cast\Array_::class:
                $castType = CompiledExpression::ARR;
                break;
            case Expr\Cast\Bool_::class:
                $castType = CompiledExpression::BOOLEAN;
                break;
            case Expr\Cast\Int_::class:
                $castType = CompiledExpression::INTEGER;
                break;
            case Expr\Cast\Double::class:
                $castType = CompiledExpression::DOUBLE;
                break;
            case Expr\Cast\Object_::class:
                $castType = CompiledExpression::OBJECT;
                break;
            case Expr\Cast\String_::class:
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
        } elseif (get_class($expr) == Expr\Cast\Unset_::class && $exprType === CompiledExpression::NULL) {
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
            Expr\Cast\Array_::class,
            Expr\Cast\Bool_::class,
            Expr\Cast\Int_::class,
            Expr\Cast\Double::class,
            Expr\Cast\Object_::class,
            Expr\Cast\String_::class,
            Expr\Cast\Unset_::class,
        ];
    }
}
