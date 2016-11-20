<?php

namespace PHPSA\Analyzer\EventListener;

use PHPSA\Analyzer\Pass\FunctionCall\PassFunctionCallInterface;
use Webiny\Component\EventManager\EventListener;

/**
 * Event listener for Scalar nodes
 */
class ScalarListener extends EventListener
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
     * @param \PHPSA\Compiler\Event\ScalarBeforeCompile $event
     */
    public function beforeCompile(\PHPSA\Compiler\Event\ScalarBeforeCompile $event)
    {
        $scalar = $event->getScalar();
        $scalarClass = get_class($scalar);

        if (!isset($this->analyzers[$scalarClass])) {
            return;
        }

        foreach ($this->analyzers[$scalarClass] as $analyzer) {
            $analyzer->pass($scalar, $event->getContext());
        }
    }
}
