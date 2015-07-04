<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use PHPSA\Visitor\Expression;
use PHPSA\Visitor\Statement;
use PhpParser\Node;

/**
 * @param $stmt
 * @return Expression|Statement
 */
function nodeVisitorFactory($stmt, Context $context)
{
    if ($stmt instanceof Node\Stmt) {
        $visitor = new Statement($stmt, $context);
        return $visitor;
    }

    $visitor = new Expression($context);
    return $visitor->compile($stmt);
}
