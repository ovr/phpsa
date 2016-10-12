<?php

namespace PHPSA\Analyzer\Pass\Statement;

use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class MissingVisibility implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for missing visibility modifiers for properties and methods.';

    /**
     * @param Stmt $stmt
     * @param Context $context
     * @return bool
     */
    public function pass(Stmt $stmt, Context $context)
    {
        // if it is private, protected or public return false
        if ($stmt->isPrivate() || $stmt->isProtected() || ($stmt->type & Class_::MODIFIER_PUBLIC) !== 0) {
            return false;
        }

        if ($stmt instanceof Property) {
            $context->notice(
                'missing_visibility',
                'Class property was defined with the deprecated var keyword. Use a visibility modifier instead.',
                $stmt
            );
        } elseif ($stmt instanceof ClassMethod) {
            $context->notice(
                'missing_visibility',
                'Class method was defined without a visibility modifier.',
                $stmt
            );
        }

        return true;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            Property::class,
            ClassMethod::class
        ];
    }
}
