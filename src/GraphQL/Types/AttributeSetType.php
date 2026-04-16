<?php

namespace App\GraphQL\Types;

use App\Models\Attributes\AttributeItem;
use App\Models\Attributes\AttributeSet;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class AttributeSetType
{
    public static function create(ObjectType $attributeItemType): ObjectType
    {
        return new ObjectType([
            'name' => 'AttributeSet',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::int()),
                    'resolve' => static fn(AttributeSet $set): int => $set->getAttributeSetId(),
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn(AttributeSet $set): string => $set->getName(),
                ],
                'type' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn(AttributeSet $set): string => $set->getType(),
                ],
                'items' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull($attributeItemType))),
                    'resolve' => static fn(AttributeSet $set): array => AttributeItem::findByAttributeSetId($set->getAttributeSetId()),
                ],
            ],
        ]);
    }
}
