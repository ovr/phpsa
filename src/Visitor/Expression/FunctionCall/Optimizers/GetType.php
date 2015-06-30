<?php

namespace PHPSA\Visitor\Expression\FunctionCall\Optimizers;

class GetType
{
    public function getPossibleValues()
    {
        return array(
            'boolean',
            'integer',
            'double',
            'string',
            'array',
            'object',
            'resource',
            'NULL',
            'unknown type',
        );
    }

    public function getReturnType()
    {
        return 'string';
    }

    public function getDeclaration()
    {
        return array(
            array(
                'type' => 'mixed'
            )
        );
    }
}
