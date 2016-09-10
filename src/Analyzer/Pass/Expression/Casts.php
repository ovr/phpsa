<?php

namespace PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use PHPSA\CompiledExpression;

class Casts implements AnalyzerPassInterface
{
    /**
     * @param Expr $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr $expr, Context $context)
    {

        $castType = CompiledExpression::UNKNOWN;

        switch (true) {
            case ($expr instanceof Expr\Cast\Array_):
                $castType = CompiledExpression::ARR;
                break;
            case ($expr instanceof Expr\Cast\Bool_):
                $castType = CompiledExpression::BOOLEAN;
                break;
            case ($expr instanceof Expr\Cast\Int_):
                $castType = CompiledExpression::INTEGER;
                break;
            case ($expr instanceof Expr\Cast\Double):
                $castType = CompiledExpression::DOUBLE;
                break;
            case ($expr instanceof Expr\Cast\Object_):
                $castType = CompiledExpression::OBJECT;
                break;
            case ($expr instanceof Expr\Cast\String_):
                $castType = CompiledExpression::STRING;
                break;
        }

        $compiledExpression = $context->getExpressionCompiler()->compile($expr->expr);
        $ExprType = $compiledExpression->getType();
        $typeName = $compiledExpression->getTypeName();
        
        if ($castType === $ExprType) {
            $context->notice(
                'stupid.cast',
                'You are trying to cast \''.$typeName.'\' to \''.$typeName.'\'.',
                $expr
            );
            return true;
        } elseif ($expr instanceof Expr\Cast\Unset_ && $ExprType === CompiledExpression::NULL) {
            $context->notice(
                'stupid.cast',
                'You are trying to cast \'unset\' to \'null\'.',
                $expr
            );
            return true;
        }
        

        return false;
    }

    /**
     * @return TreeBuilder
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
