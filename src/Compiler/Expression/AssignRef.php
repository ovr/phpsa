<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PhpParser\Node\Expr\Variable as VariableNode;

class AssignRef extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\AssignRef';

    /**
     * $a &= $b;
     *
     * @param \PhpParser\Node\Expr\AssignRef $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiler = $context->getExpressionCompiler();
        if ($expr->var instanceof VariableNode) {
            $name = $expr->var->name;

            $compiledExpression = $compiler->compile($expr->expr);
            
            $symbol = $context->getSymbol($name);
            if ($symbol) {
                $symbol->modify($compiledExpression->getType(), $compiledExpression->getValue());
            } else {
                $symbol = new \PHPSA\Variable(
                    $name,
                    $compiledExpression->getValue(),
                    $compiledExpression->getType(),
                    $context->getCurrentBranch()
                );
                $context->addVariable($symbol);
            }

            if ($expr->expr instanceof VariableNode) {
                $rightVarName = $expr->expr->name;

                $rightSymbol = $context->getSymbol($rightVarName);
                if ($rightSymbol) {
                    $rightSymbol->incUse();
                    $symbol->setReferencedTo($rightSymbol);
                } else {
                    $context->debug('Cannot fetch variable by name: ' . $rightVarName);
                }
            }

            $symbol->incSets();
            return $compiledExpression;
        }

        $context->debug('Unknown how to pass symbol by ref');
        return new CompiledExpression();
    }
}
