<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Class_;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class OldConstructor implements AnalyzerPassInterface
{

    /**
     * @param Class_ $classStmt
     * @param Context $context
     * @return bool
     */
    public function pass(Class_ $classStmt, Context $context)
    {
        foreach ($classStmt->stmts as $statement) {
            if (!($statement instanceof ClassMethod) || $statement->name !== $classStmt->name) {
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
            Class_::class
        ];
    }
}
