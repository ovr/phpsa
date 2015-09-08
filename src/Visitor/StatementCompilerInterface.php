<?php
/**
 * Created by PhpStorm.
 * User: ovr
 * Date: 06.07.15
 * Time: 16:22
 */

namespace PHPSA\Visitor;

use PHPParser\Node\Stmt;
use PHPSA\Context;

interface StatementCompilerInterface
{
    public function pass(Stmt $expr, Context $context);
}
