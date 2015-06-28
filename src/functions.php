<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use PHPSA\Visitor\Expression;
use PHPSA\Visitor\Statement;
use PhpParser\Node;

/**
 * @param $st
 * @return Expression|Statement
 */
function nodeVisitorFactory($st, Context $context)
{
    if ($st instanceof Node\Stmt) {
        $visitor = new Statement($st, $context);
        return $visitor;
    }

    $visitor = new Expression($context);
    return $visitor->compile($st);
}
