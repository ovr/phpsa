<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PhpParser\Node;

class Assign extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Assign';

    /**
     * $a = 3;
     *
     * @param \PhpParser\Node\Expr\Assign $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiler = $context->getExpressionCompiler();

        $compiledExpression = $compiler->compile($expr->expr);

        if ($expr->var instanceof Node\Expr\List_) {
            $isCorrectType = $compiledExpression->isArray();

            foreach ($expr->var->vars as $key => $var) {
                if (!$var instanceof Node\Expr\Variable) {
                    continue;
                }

                if ($var->name instanceof Node\Expr\Variable) {
                    $this->compileVariableDeclaration($compiler->compile($var->name), new CompiledExpression(), $context);
                    continue;
                }

                $symbol = $context->getSymbol($var->name);
                if (!$symbol) {
                    $symbol = new \PHPSA\Variable(
                        $var->name,
                        null,
                        CompiledExpression::UNKNOWN,
                        $context->getCurrentBranch()
                    );
                    $context->addVariable($symbol);
                }

                if (!$isCorrectType) {
                    $symbol->modify(CompiledExpression::NULL, null);
                }

                $symbol->incSets();
            }

            return new CompiledExpression();
        }

        if ($expr->var instanceof Node\Expr\Variable) {
            $this->compileVariableDeclaration($compiler->compile($expr->var->name), $compiledExpression, $context);

            return $compiledExpression;
        }

        if ($expr->var instanceof Node\Expr\PropertyFetch) {
            $compiledExpression = $compiler->compile($expr->var->var);
            if ($compiledExpression->getType() == CompiledExpression::OBJECT) {
                $objectDefinition = $compiledExpression->getValue();
                if ($objectDefinition instanceof ClassDefinition) {
                    if (is_string($expr->var->name)) {
                        if ($objectDefinition->hasProperty($expr->var->name)) {
                            return $compiler->compile($objectDefinition->getProperty($expr->var->name));
                        }
                    }
                }
            }
        }

        $context->debug('Unknown how to pass symbol', $expr);
        return new CompiledExpression();
    }


    protected function compileVariableDeclaration(CompiledExpression $variableName, CompiledExpression $value, Context $context)
    {
        switch ($variableName->getType()) {
            case CompiledExpression::STRING:
                break;
            default:
                $context->debug('Unexpected type of Variable name after compile');
                return new CompiledExpression();
        }

        $symbol = $context->getSymbol($variableName->getValue());
        if ($symbol) {
            $symbol->modify($value->getType(), $value->getValue());
            $context->modifyReferencedVariables(
                $symbol,
                $value->getType(),
                $value->getValue()
            );
        } else {
            $symbol = new \PHPSA\Variable(
                $variableName->getValue(),
                $value->getValue(),
                $value->getType(),
                $context->getCurrentBranch()
            );
            $context->addVariable($symbol);
        }

        $symbol->incSets();
    }
}
