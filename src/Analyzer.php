<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */


namespace PHPSA;

use PHPSA\Analyzer\EventListener\ExpressionListener;
use PHPSA\Analyzer\EventListener\StatementListener;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use Webiny\Component\EventManager\EventManager;

class Analyzer
{
    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var []AnalyzerPassInterface[]
     */
    protected $bindOnExpressions = [];

    /**
     * @var []AnalyzerPassInterface[]
     */
    protected $bindOnStatements = [];

    public function __construct(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    public function registerExpressionPasses(array $expressionPasses)
    {
        foreach ($expressionPasses as $pass) {
            if (!$pass instanceof  AnalyzerPassInterface) {
                throw new \RuntimeException('Analyzer pass must implement AnalyzerPassInterface');
            }

            $bindOnExpressions = $pass->getRegister();
            foreach ($bindOnExpressions as $bindOnExpression) {
                if (isset($this->bindOnExpressions[$bindOnExpression])) {
                    $this->bindOnExpressions[$bindOnExpression][] = $pass;
                } else {
                    $this->bindOnExpressions[$bindOnExpression] = [$pass];
                }
            }
        }
    }

    /**
     * @param array $statementPasses
     */
    public function registerStatementPasses(array $statementPasses)
    {
        foreach ($statementPasses as $pass) {
            if (!$pass instanceof  AnalyzerPassInterface) {
                throw new \RuntimeException('Analyzer pass must implement AnalyzerPassInterface');
            }

            $bindOnStatements = $pass->getRegister();
            foreach ($bindOnStatements as $bindOnStatement) {
                if (isset($this->bindOnStatements[$bindOnStatement])) {
                    $this->bindOnStatements[$bindOnStatement][] = $pass;
                } else {
                    $this->bindOnStatements[$bindOnStatement] = [$pass];
                }
            }
        }
    }

    public function bind()
    {
        $this->eventManager->listen(Compiler\Event\ExpressionBeforeCompile::EVENT_NAME)
            ->handler(
                new ExpressionListener($this->bindOnExpressions)
            )
            ->method('beforeCompile');

        $this->eventManager->listen(Compiler\Event\StatementBeforeCompile::EVENT_NAME)
            ->handler(
                new StatementListener($this->bindOnStatements)
            )
            ->method('beforeCompile');
    }
}
