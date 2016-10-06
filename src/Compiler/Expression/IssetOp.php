<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PhpParser\Node\Expr\Variable as VariableNode;
use PhpParser\Node\Name;

class IssetOp extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Isset_';

    /**
     * isset({expr]})
     *
     * @param \PhpParser\Node\Expr\Isset_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $result = false;

        foreach ($expr->vars as $var) {
            if ($var instanceof VariableNode) {
                $varName = $var->name;

                if ($varName instanceof Name) {
                    $varName = $varName->parts[0];
                }

                $variable = $context->getSymbol($varName);

                if ($variable) {
                    $variable->incUse();

                    if ($variable->getValue() !== null) {
                        $result = true;
                        continue; // this variable is set, continue
                    }
                }
                return CompiledExpression::fromZvalValue(false); // one of the vars is not set
            }
        }

        return CompiledExpression::fromZvalValue($result); // if all are set return true, else false
    }
}
