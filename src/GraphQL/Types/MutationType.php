<?php

namespace App\GraphQL\Types;

use App\Models\Order;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class MutationType
{
    public static function create(): ObjectType
    {
        $cartLineInputType = new InputObjectType([
            'name' => 'CartLineInput',
            'fields' => [
                'productId' => [
                    'type' => Type::nonNull(Type::string()),
                ],
                'quantity' => [
                    'type' => Type::nonNull(Type::int()),
                ],
                'selectedAttributeItemIds' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(Type::string()))),
                ],
            ],
        ]);

        $placeOrderPayloadType = new ObjectType([
            'name' => 'PlaceOrderPayload',
            'fields' => [
                'success' => [
                    'type' => Type::nonNull(Type::boolean()),
                ],
                'orderId' => [
                    'type' => Type::int(),
                ],
                'errors' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(Type::string()))),
                ],
            ],
        ]);

        return new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'placeOrder' => [
                    'type' => Type::nonNull($placeOrderPayloadType),
                    'args' => [
                        'items' => [
                            'type' => Type::nonNull(Type::listOf(Type::nonNull($cartLineInputType))),
                        ],
                    ],
                    'resolve' => static fn($_, array $args): array => (new Order())->placeOrder($args['items']),
                ],
            ],
        ]);
    }
}
