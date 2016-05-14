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

        if ($expressionClass != Node\Expr\FuncCall::class) {
            return;
        }

        /** @var PassFunctionCallInterface $analyzer */
        foreach ($this->analyzers as $analyzer) {
            $analyzer->visitPhpFunctionCall($expression, $event->getContext());
        }
    }
}
