<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class CurrencyType
{
    public static function create(): ObjectType
    {
        return new ObjectType([
            'name' => 'Currency',
            'fields' => [
                'label' => Type::nonNull(Type::string()),
                'symbol' => Type::nonNull(Type::string()),
            ],
        ]);
    }
}
