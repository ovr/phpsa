<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class MissingDocblock implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for a missing docblock for: class, property, class constant, trait, interface, class method, function.';

    /**
     * @param Stmt $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(Stmt $stmt, Context $context)
    {
        if ($stmt->getDocComment() === null) {
            $context->notice(
                'missing_docblock',
                'Missing Docblock',
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
            Stmt\Class_::class,
            Stmt\ClassMethod::class,
            Stmt\Property::class,
            Stmt\Function_::class,
            Stmt\Trait_::class,
            Stmt\Interface_::class,
            Stmt\ClassConst::class,
        ];
    }
}
