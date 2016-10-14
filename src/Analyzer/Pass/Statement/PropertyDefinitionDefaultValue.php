<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass;
use PHPSA\Context;
use PHPSA\CompiledExpression;

class PropertyDefinitionDefaultValue implements Pass\AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks if any Property Definition is done with a default null value (not needed). For example: `$a = null`';

    /**
     * @param $stmt
     * @param Context $context
     * @return bool
     */
    public function pass($stmt, Context $context)
    {
        if ($stmt->default instanceof Node\Expr) {
            $compiled = $context->getExpressionCompiler()->compile($stmt->default);
            if ($compiled->getType() == CompiledExpression::NULL) {
                $context->notice(
                    'property_definition_default_value',
                    'null is default and is not needed.',
                    $stmt
                );
                return true;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            PropertyProperty::class,
        ];
    }
}
