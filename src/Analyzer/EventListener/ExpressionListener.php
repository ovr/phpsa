<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\EventListener;

use PhpParser\Node;
use PHPSA\Analyzer\Pass\FunctionCall\PassFunctionCallInterface;
use Webiny\Component\EventManager\EventListener;

class ExpressionListener extends EventListener
{
    /**
     * @var array
     */
    private $analyzers;

    public function __construct(array $analyzers)
    {
        $this->analyzers = $analyzers;
    }

    public function beforeCompile(\PHPSA\Compiler\Event\ExpressionBeforeCompile $event)
    {
        $expression = $event->getExpression();
        $expressionClass = get_class($expression);

        if (!isset($this->analyzers[$expressionClass])) {
            return;
        }

        foreach ($this->analyzers[$expressionClass] as $analyzer) {
            $analyzer->pass($expression, $event->getContext());
        }
    }
}
