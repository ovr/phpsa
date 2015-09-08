<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Visitor\Expression\AbstractCompiler;

class Return_ extends AbstractCompiler
{
    protected $name = '\PhpParser\Node\Stmt\Return_';

    /**
     * @param \PhpParser\Node\Stmt\Return_ $expr
     * @param Context $context
     * @return CompiledExpression
     */
    public function compile($expr, Context $context)
    {
        return new CompiledExpression();
    }
}
