<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\EventListener;

use PHPSA\Analyzer\Pass\FunctionCall\PassFunctionCallInterface;
use Webiny\Component\EventManager\EventListener;

/**
 * Event listener for Expression nodes
 */
class ExpressionListener extends EventListener
{
    /**
     * @var array
     */
    private $analyzers;

    /**
     * @param array $analyzers
     */
    public function __construct(array $analyzers)
    {
        $this->analyzers = $analyzers;
    }

    /**
     * @param \PHPSA\Compiler\Event\ExpressionBeforeCompile $event
     */
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
