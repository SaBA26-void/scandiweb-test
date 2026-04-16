<?php

namespace App\GraphQL\Types;

use App\Models\Attributes\AttributeSet;
use App\Models\Attributes\ProductAttributeValue;
use App\Models\Price;
use App\Models\ProductImage;
use App\Models\Products\Product;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class ProductType
{
    public static function create(
        ObjectType $galleryType,
        ObjectType $priceType,
        ObjectType $attributeSetType,
    ): ObjectType {
        return new ObjectType([
            'name' => 'Product',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn(Product $p): string => $p->getId(),
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn(Product $p): string => $p->getName(),
                ],
                'brand' => [
                    'type' => Type::string(),
                    'resolve' => static fn(Product $p): string => $p->getBrand(),
                ],
                'inStock' => [
                    'type' => Type::nonNull(Type::boolean()),
                    'resolve' => static fn(Product $p): bool => $p->isInStock(),
                ],
                'description' => [
                    'type' => Type::string(),
                    'resolve' => static fn(Product $p): string => $p->getDescription(),
                ],
                'category' => [
                    'type' => Type::string(),
                    'resolve' => static fn(Product $p): string => $p->getCategoryName(),
                ],
                'gallery' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull($galleryType))),
                    'resolve' => static fn(Product $p): array => ProductImage::findByProductId($p->getId()),
                ],
                'prices' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull($priceType))),
                    'resolve' => static fn(Product $p): array => Price::findByProductId($p->getId()),
                ],
                'attributes' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull($attributeSetType))),
                    'resolve' => static function (Product $p): array {
                        $values = ProductAttributeValue::findByProductId($p->getId());
                        if ($values === []) {
                            return [];
                        }

                        $bySet = [];
                        foreach ($values as $v) {
                            $bySet[$v->getAttributeSetId()] = true;
                        }

                        $result = [];
                        foreach (array_keys($bySet) as $setId) {
                            $set = AttributeSet::findById((int)$setId);
                            if ($set !== null) {
                                $result[] = $set;
                            }
                        }

                        return $result;
                    },
                ],
            ],
        ]);
    }
}
