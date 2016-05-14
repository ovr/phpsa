<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\EventListener;

use Webiny\Component\EventManager\EventListener;

class ExpressionListener extends EventListener
{
    public function beforeCompile(\PHPSA\Compiler\Event\ExpressionBeforeCompile $event)
    {
        
    }
}
