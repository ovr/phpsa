<?php

namespace PHPSA\Analyzer\Pass\Statement;

use phpDocumentor\Reflection\DocBlockFactory;
use PhpParser\Node\Stmt\ClassMethod;
use PHPSA\Analyzer\Helper\DefaultMetadataPassTrait;
use PHPSA\Analyzer\Pass\AnalyzerPassInterface;
use PHPSA\Context;

class TestAnnotation implements AnalyzerPassInterface
{
    use DefaultMetadataPassTrait;

    const DESCRIPTION = 'Checks for use of `@test` when methods name begins with test, since it is unnecessary.';

    /** @var DocBlockFactory */
    protected $docBlockFactory;

    /**
     * Creates a DocBlockFactory
     */
    public function __construct()
    {
        $this->docBlockFactory = DocBlockFactory::createInstance();
    }

    /**
     * @param ClassMethod $methodStmt
     * @param Context $context
     * @return bool
     */
    public function pass(ClassMethod $methodStmt, Context $context)
    {
        $functionName = $methodStmt->name;
        if (!$functionName) {
            return false;
        }

        if (substr($functionName, 0, 4) !== 'test') {
            return false;
        }

        if ($methodStmt->getDocComment()) {
            $phpdoc = $this->docBlockFactory->create($methodStmt->getDocComment()->getText());

            if ($phpdoc->hasTag('test')) {
                $context->notice(
                    'test.annotation',
                    'Annotation @test is not needed when the method is prefixed with test.',
                    $methodStmt
                );
                return true;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getRegister()
    {
        return [
            ClassMethod::class
        ];
    }
}
