<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace Tests\Compiling\Statements;

use Exception;
use RuntimeException;

class TryCatch
{
    /**
     * @return string
     */
    public function simpleTryCatch()
    {
        try {
            throw new RuntimeException('Oops!');
        } catch (Exception $e) {
            return $e->getMessage();
        } finally {
            return "meow";
        }
    }
}
