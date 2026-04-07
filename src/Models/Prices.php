<?php

namespace App\Models;

use PDO;
use App\Database\Connection;

class Prices
{
    private int $priceId;
    private string $productId;
    private int $currencyId;
    private float $amount;
    private readonly Connection $db;

    public function __construct(array $data = [])
    {
        $this->db = Connection::getInstance();
        $this->priceId = $data['price_id'] ?? 0;
        $this->productId = $data['product_id'] ?? '';
        $this->currencyId = $data['currency_id'] ?? 0;
        $this->amount = (float)($data['amount'] ?? 0);
    }

    public function getPriceId(): int
    {
        return $this->priceId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getCurrencyId(): int
    {
        return $this->currencyId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function toArray(): array
    {
        return [
            'price_id'   => $this->priceId,
            'product_id' => $this->productId,
            'currency_id' => $this->currencyId,
            'amount'     => $this->amount
        ];
    }

    public static function getPricesByProduct(string $productId): array
    {
        $instance = new self();

        $rows = $instance->db->query(
            "SELECT price_id, product_id, currency_id, amount 
             FROM prices 
             WHERE product_id = :product_id",
            ['product_id' => $productId]
        );

        // temp fix
        return [];
    }

    public static function findPrice(string $productId, int $currencyId): ?Prices
    {
        $instance = new self();

        $stmt = $instance->db->query(
            "SELECT price_id, product_id, currency_id, amount 
             FROM prices 
             WHERE product_id = :product_id AND currency_id = :currency_id",
            [
                'product_id' => $productId,
                'currency_id' => $currencyId
            ]
        );

        // temp fix
        return null;
    }
}
