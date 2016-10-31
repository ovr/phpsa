<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Helper;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Stmt\Return_;
use PHPSA\Context;

trait ResolveExpressionTrait
{
    /**
     * @param FuncCall $funcCall
     * @param Context $context
     * @return string|bool
     */
    public function resolveFunctionName(FuncCall $funcCall, Context $context)
    {
        $funcNameCompiledExpression = $context->getExpressionCompiler()->compile($funcCall->name);

        if ($funcNameCompiledExpression->isString() && $funcNameCompiledExpression->isCorrectValue()) {
            return $funcNameCompiledExpression->getValue();
        } else if (!$funcNameCompiledExpression->isCallable()) {
            $context->debug(
                'Unexpected function name type ' . $funcNameCompiledExpression->getTypeName(),
                $funcCall->name
            );
        }

        return false;
    }

    /**
     * Return \Generator with Yield_ expression(s)
     *
     * @param \PhpParser\Node[] $nodes
     * @return \Generator
     */
    protected function findYieldExpression(array $nodes)
    {
        return $this->findNode($nodes, Yield_::class);
    }

    /**
     * @param \PhpParser\Node[] $nodes
     * @return \Generator
     */
    protected function findReturnStatement(array $nodes)
    {
        return $this->findNode($nodes, Return_::class);
    }

    /**
     * @param array $nodes
     * @param string $nodeName Class name of Node(s) what We should return
     * @return \Generator
     */
    protected function findNode(array $nodes, $nodeName)
    {
        foreach ($this->traverseArray($nodes) as $node) {
            if (get_class($node) === $nodeName) {
                yield $node;
            }
        }
    }

    /**
     * For the code above
     * Я атеист, но когда я начинал это писать, только Бог и я понимали, что я делаю
     * Сейчас остался только Бог
     *
     * @param Node $node
     * @return \Generator
     *
     * @todo After move to PHP 7.0+ use yield from
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
                yield $subNode;

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
                yield $node;

                foreach ($this->traverseNode($node) as $rNode) {
                    yield $rNode;
                }
            }
        }
    }
}
