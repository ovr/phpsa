<?php
/**
 * @author Strahinja Djuric https://github.com/kilgaloon <radju22@gmail.com>
 */
namespace PHPSA\Analyzer\Pass\Expression;

use PHPSA\Context;
use PHPSA\Analyzer\Pass;
use PhpParser\Node\Expr\Closure;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;

class DuplicatedVariablesInUseClosure implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Check for duplicate variables in use statement';

    /**
     * @param Closure $funcCall
     * @param Context $context
     * @return bool
     */
    public function pass(Closure $expr, Context $context)
    {
        $varUsed = [];
        foreach ($expr->uses as $use) {
            $var = $context->getExpressionCompiler()->compile($use->var);
            if (in_array($var->getValue(), $varUsed)) {
                $context->notice(
                    'duplicated_variable_in_use_closure',
                    sprintf("Duplicated variable $%s in use statement.", $use->var),
                    $expr
                );

                return false;
            } else {
                array_push($varUsed, $use->var);
            }
        }

        return true;
    }

    public function getRegister()
    {
        return [
            Closure::class
        ];
    }
}
