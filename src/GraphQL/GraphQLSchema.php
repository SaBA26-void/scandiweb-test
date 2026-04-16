<?php

namespace App\GraphQL;

use App\GraphQL\Types\AttributeItemType;
use App\GraphQL\Types\AttributeSetType;
use App\GraphQL\Types\CategoryType;
use App\GraphQL\Types\CurrencyType;
use App\GraphQL\Types\GalleryImageType;
use App\GraphQL\Types\PriceType;
use App\GraphQL\Types\ProductType;
use App\GraphQL\Types\QueryType;
use GraphQL\Type\Schema;

final class GraphQLSchema
{
    public static function create(): Schema
    {
        $currencyType = CurrencyType::create();
        $priceType = PriceType::create($currencyType);
        $galleryType = GalleryImageType::create();
        $attributeItemType = AttributeItemType::create();
        $attributeSetType = AttributeSetType::create($attributeItemType);
        $productType = ProductType::create($galleryType, $priceType, $attributeSetType);
        $categoryType = CategoryType::create($productType);
        $queryType = QueryType::create($categoryType, $productType);

        return new Schema(['query' => $queryType]);
    }
}
