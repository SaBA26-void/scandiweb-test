<?php

namespace App\Models;


use App\Models\AbstractModel;

class Prices extends AbstractModel
{
    private int $priceId;
    private string $productId;
    private int $currencyId;
    private float $amount;

    public function __construct(array $data = [])
    {
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

    public static function getPricesByProduct(?string $productId = null): array
    {
        $instance = new self();

        $rows = $instance->db->query(
            "SELECT price_id, product_id, currency_id, amount 
             FROM prices 
             WHERE product_id = :product_id",
            ['product_id' => $productId]
        );

        return !empty($rows) ? array_map(
            static fn(array $row) => new self($row['price_id'], $row['product_id'], $row['currnecy_id'], $row['amount']),
            $rows
        ) : [];
    }

    public static function findPrice(string $productId, int $currencyId): array
    {
        $instance = new self();

        $rows = $instance->db->query(
            "SELECT price_id, product_id, currency_id, amount 
             FROM prices 
             WHERE product_id = :product_id AND currency_id = :currency_id",
            [
                'product_id' => $productId,
                'currency_id' => $currencyId
            ]
        );

        return !empty($rows) ? array_map(
            static fn(array $row) => new self($row['price_id'], $row['product_id'], $row['currency_id'], $row['amount']),
            $rows
        ) : [];
    }
}
