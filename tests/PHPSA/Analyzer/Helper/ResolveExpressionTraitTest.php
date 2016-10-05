<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace Tests\PHPSA\Analyzer\Helper;

use PHPSA\Analyzer\Helper\ResolveExpressionTrait;
use PHPSA\CompiledExpression;

class ResolveExpressionTraitTest extends \Tests\PHPSA\TestCase
{
    use ResolveExpressionTrait;

    public function getDataProviderWithFunctionCallExpr()
    {
        $context = $this->getContext();
        $context->addVariable(
            new \PHPSA\Variable(
                'variableWithFunctionName',
                'testFunctionName',
                CompiledExpression::STRING
            )
        );

        return [
            [
                new \PhpParser\Node\Name(['testFn']),
                'testFn',
                $context
            ],
            [
                new \PhpParser\Node\Expr\Variable('unknown'),
                false,
                $context
            ],
            [
                new \PhpParser\Node\Expr\Variable('variableWithFunctionName'),
                'testFunctionName',
                $context
            ]
        ];
    }

    /**
     * @dataProvider getDataProviderWithFunctionCallExpr
     *
     * @param $nameExpr
     * @param $expectedFunctionName
     */
    public function testResolveFunctionName($nameExpr, $expectedFunctionName, $context)
    {
        parent::assertSame(
            $expectedFunctionName,
            $this->resolveFunctionName(
                new \PhpParser\Node\Expr\FuncCall(
                    $nameExpr
                ),
                $context
            )
        );
    }
}
