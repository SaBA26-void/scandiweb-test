<?php

namespace App\GraphQL\Types;

use App\Models\Category;
use App\Models\Products\Product;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class QueryType
{
    public static function create(ObjectType $categoryType, ObjectType $productType): ObjectType
    {
        return new ObjectType([
            'name' => 'Query',
            'fields' => [
                'categories' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull($categoryType))),
                    'resolve' => static fn(): array => Category::findAll(),
                ],
                'products' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull($productType))),
                    'args' => [
                        'category' => ['type' => Type::string()],
                    ],
                    'resolve' => static fn($_, array $args): array => Product::findAll($args['category'] ?? null),
                ],
                'product' => [
                    'type' => $productType,
                    'args' => [
                        'id' => ['type' => Type::nonNull(Type::string())],
                    ],
                    'resolve' => static fn($_, array $args): ?Product => Product::findById($args['id']),
                ],
            ],
        ]);
    }
}
