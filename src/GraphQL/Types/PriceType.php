<?php

namespace App\GraphQL\Types;

use App\Models\Currency;
use App\Models\Price;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class PriceType
{
    public static function create(ObjectType $currencyType): ObjectType
    {
        return new ObjectType([
            'name' => 'Price',
            'fields' => [
                'amount' => [
                    'type' => Type::nonNull(Type::float()),
                    'resolve' => static fn(Price $p): float => $p->getAmount(),
                ],
                'currency' => [
                    'type' => Type::nonNull($currencyType),
                    'resolve' => static function (Price $price): ?array {
                        $currency = null;
                        foreach (Currency::findAll() as $c) {
                            if ($c->getCurrencyId() === $price->getCurrencyId()) {
                                $currency = $c;
                                break;
                            }
                        }
                        return $currency?->toArray();
                    },
                ],
            ],
        ]);
    }
}
