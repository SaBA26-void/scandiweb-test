<?php

namespace App\GraphQL\Types;

use App\Models\Attributes\AttributeItem;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class AttributeItemType
{
    public static function create(): ObjectType
    {
        return new ObjectType([
            'name' => 'AttributeItem',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn(AttributeItem $item): string => $item->getAttributeItemId(),
                ],
                'value' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn(AttributeItem $item): string => $item->getValue(),
                ],
                'displayValue' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn(AttributeItem $item): string => $item->getDisplayValue(),
                ],
            ],
        ]);
    }
}
