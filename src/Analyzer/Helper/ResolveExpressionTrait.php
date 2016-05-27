<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Helper;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Return_;
use PHPSA\Context;

trait ResolveExpressionTrait
{
    /**
     * @param FuncCall $funcCall
     * @param Context $context
     * @return string|bool
     * @throws \PHPSA\Exception\RuntimeException
     */
    public function resolveFunctionName(FuncCall $funcCall, Context $context)
    {
        $funcNameCompiledExpression = $context->getExpressionCompiler()->compile($funcCall->name);

        if ($funcNameCompiledExpression->isString() && $funcNameCompiledExpression->isCorrectValue()) {
            return $funcNameCompiledExpression->getValue();
        } else {
            $context->debug(
                'Unexpected function name type ' . $funcNameCompiledExpression->getType(),
                $funcCall->name
            );
        }

        return false;
    }

    /**
     * @param \PhpParser\Node[] $nodes
     * @return \PhpParser\Node\Stmt\Return_
     */
    protected function findReturnStatement(array $nodes)
    {
        foreach ($this->traverseArray($nodes) as $node) {
            if ($node instanceof Return_) {
                yield $node;
            }
        }
    }

    /**
     * For the code above
     * Я атеист, но когда я начинал это писать, только Бог и я понимали, что я делаю
     * Сейчас остался только Бог
     */

    /**
     * @param Node $node
     * @return \Generator
     */
    protected function traverseNode(Node $node)
    {
        foreach ($node->getSubNodeNames() as $name) {
            $subNode = &$node->$name;

            if (is_array($subNode)) {
                foreach ($this->traverseArray($subNode) as $rNode) {
                    yield $rNode;
                }
            } elseif ($subNode instanceof Node) {
                yield $node;

                foreach ($this->traverseNode($subNode) as $rNode) {
                    yield $rNode;
                }
            }
        }
    }

    /**
     * @param array $nodes
     * @return \Generator
     */
    protected function traverseArray(array $nodes)
    {
        foreach ($nodes as $node) {
            if (is_array($node)) {
                foreach ($this->traverseArray($node) as $rNode) {
                    yield $rNode;
                }
            } elseif ($node instanceof Node) {
                foreach ($this->traverseNode($node) as $rNode) {
                    yield $rNode;
                }
            }
        }
    }
}
