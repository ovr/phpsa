<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace Tests\PHPSA\Analyzer\Pass\Expression;

use PhpParser\Node\Expr\Array_;
use PHPSA\Analyzer\Pass\Expression\ArrayShortDefinition;

class ArrayShortDefinitionTest extends \Tests\PHPSA\TestCase
{
    public function testLongDefintion()
    {
        $analyzer = new ArrayShortDefinition();
        $result = $analyzer->pass(
            new Array_(
                [],
                [
                    /**
                     * $a = array();
                     */
                    'kind' => Array_::KIND_LONG
                ]
            ),
            $this->getContext()
        );
        self::assertTrue($result);
    }

    public function testShortDefintion()
    {
        $analyzer = new ArrayShortDefinition();
        $result = $analyzer->pass(
            new Array_(
                [],
                [
                    /**
                     * $a = [];
                     */
                    'kind' => Array_::KIND_SHORT
                ]
            ),
            $this->getContext()
        );
        self::assertFalse($result);
    }
}
