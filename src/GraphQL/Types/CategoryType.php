<?php

namespace App\GraphQL\Types;

use App\Models\Category;
use App\Models\Products\Product;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class CategoryType
{
    public static function create(ObjectType $productType): ObjectType
    {
        return new ObjectType([
            'name' => 'Category',
            'fields' => [
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn(Category $c): string => $c->getName(),
                ],
                'products' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull($productType))),
                    'resolve' => static fn(Category $category): array => Product::findAll($category->getName()),
                ],
            ],
        ]);
    }
}
