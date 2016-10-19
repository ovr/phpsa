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
        $context->addVariable(
            new \PHPSA\Variable(
                'variableWithIntValue',
                12345,
                CompiledExpression::INTEGER
            )
        );

        return [
            // success
            [
                new \PhpParser\Node\Name(['testFn']),
                'testFn',
                $context
            ],
            [
                new \PhpParser\Node\Expr\Variable('variableWithFunctionName'),
                'testFunctionName',
                $context
            ],
            // not success
            [
                new \PhpParser\Node\Expr\Variable('unknown'),
                false,
                $context
            ],
            [
                new \PhpParser\Node\Expr\Variable('variableWithIntValue'),
                false,
                $context
            ],
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

    public function testFindReturnStatement()
    {
        $returnStatement = new \PhpParser\Node\Stmt\Return_();

        parent::assertSame(
            [$returnStatement],
            // findReturnStatement will return \Generator, We should iterate it
            iterator_to_array(
                $this->findReturnStatement(
                    [
                        $returnStatement
                    ]
                )
            )
        );
    }

    public function testFindYieldStatement()
    {
        $returnStatement = new \PhpParser\Node\Expr\Yield_();

        parent::assertSame(
            [$returnStatement],
            // findReturnStatement will return \Generator, We should iterate it
            iterator_to_array(
                $this->findYieldExpression(
                    [
                        $returnStatement
                    ]
                )
            )
        );
    }
}
