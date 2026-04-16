<?php

namespace App\GraphQL\Types;

use App\Models\ProductImage;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class GalleryImageType
{
    public static function create(): ObjectType
    {
        return new ObjectType([
            'name' => 'GalleryImage',
            'fields' => [
                'url' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn(ProductImage $img): string => $img->getProductImageUrl(),
                ],
            ],
        ]);
    }
}
