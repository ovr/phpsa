<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Context;

class MissingBody implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks that statements that define a block of statements are not empty.';
    
    /**
     * @param Stmt $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(Stmt $stmt, Context $context)
    {
        if ($stmt instanceof Stmt\ClassMethod && $stmt->isAbstract()) { // abstract classes are ok
            return false;
        }

        if ($stmt instanceof Stmt\Switch_) {
            $counting = $stmt->cases;
        } else {
            $counting = $stmt->stmts;
        }

        if (count($counting) === 0) {
            $context->notice(
                'missing_body',
                'Missing Body',
                $stmt
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
            Stmt\Function_::class,
            Stmt\ClassMethod::class,
            Stmt\For_::class,
            Stmt\Foreach_::class,
            Stmt\While_::class,
            Stmt\Do_::class,
            Stmt\If_::class,
            Stmt\ElseIf_::class,
            Stmt\Else_::class,
            Stmt\Switch_::class,
            Stmt\TryCatch::class,
            Stmt\Catch_::class,
        ];
    }
}
