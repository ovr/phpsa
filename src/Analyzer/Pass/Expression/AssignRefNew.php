<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\Expression;

use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PhpParser\Node\Expr;
use PHPSA\Context;

class AssignRefNew implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait {
        DefaultMetadataPassTrait::getMetadata as defaultMetadata;
    }

    const DESCRIPTION = 'Protection of usage & and new.';

    /**
     * @param Expr\AssignRef $expr
     * @param Context $context
     * @return bool
     */
    public function pass(Expr\AssignRef $expr, Context $context)
    {
        if ($expr->expr instanceof Expr\New_) {
            $context->notice(
                'assign_ref_new',
                'Do not use = & new, all objects in PHP are passed by reference',
                $expr
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
            Expr\AssignRef::class
        ];
    }
}
