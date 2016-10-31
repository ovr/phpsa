<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA;

use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Statement;
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

    return $context->getExpressionCompiler()->compile($stmt);
}

/**
 * Especial to protect HHVM: exception: Need to call next() first
 *
 * @param \Generator $generator
 * @return bool
 */
function generatorHasValue(\Generator $generator)
{
    foreach ($generator as $v) {
        return true;
    }

    return false;
}
