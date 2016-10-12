<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class OldConstructor implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for use of PHP 4 constructors and discourages it.';

    /**
     * @param Stmt\Class_ $classStmt
     * @param Context $context
     * @return bool
     */
    public function pass(Stmt\Class_ $classStmt, Context $context)
    {
        foreach ($classStmt->stmts as $statement) {
            if (!($statement instanceof Stmt\ClassMethod) || $statement->name !== $classStmt->name) {
                continue;
            }
            $context->notice(
                'deprecated.constructor',
                sprintf('Class %s uses a PHP4 constructor.', $classStmt->name),
                $classStmt
            );
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Stmt\Class_::class
        ];
    }
}
