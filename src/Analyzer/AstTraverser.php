<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer;

use PHPSA\Context;

class AstTraverser extends \PhpParser\NodeTraverser
{
    /**
     * @var bool
     */
    private $cloneNodes = false;

    /**
     * @param array $visitors
     * @param Context $context
     */
    public function __construct(array $visitors, Context $context)
    {
        foreach ($visitors as $visitor) {
            $visitor->setContext($context);
        }

        $this->visitors = $visitors;
    }
}
