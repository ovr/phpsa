<?php

namespace Tests\PHPSA\Expression\Operators\Comparison;

use PhpParser\Node;
use PHPSA\CompiledExpression;
use PHPSA\Visitor\Expression;

class SmallerEqualTest extends BaseTestCase
{
    protected function operator($a, $b)
    {
        return $a <= $b;
    }
}
