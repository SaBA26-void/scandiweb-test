<?php


namespace App\Models;


class Price extends AbstractModel
{

    private int $priceId;
    private string $productId;
    private int $currencyId;
    private float $amount;


    public function __construct(array $data = [])
    {
        parent::__construct();

        $this->priceId = (int) ($data['price_id'] ?? 0);
        $this->productId = (string) ($data['product_id'] ?? '');
        $this->currencyId = (int) ($data['currency_id'] ?? 0);
        $this->amount = (float) ($data['amount'] ?? 0);
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
            'price_id' => $this->priceId,
            'product_id' => $this->productId,
            'currency_id' => $this->currencyId,
            'amount' => $this->amount,
        ];
    }



    public static function findByProductId(string $productId): array
    {
        $instance = new self();
        $rows = $instance->db->query(
            'SELECT price_id, product_id, currency_id, amount
             FROM prices
             WHERE product_id = :product_id',
            ['product_id' => $productId]
        );

        return array_map(
            static fn(array $row) => new self($row),
            $rows
        );
    }


    public static function findByProductAndCurrency(string $productId, int $currencyId): ?self
    {
        $instance = new self();
        $rows = $instance->db->query(
            'SELECT price_id, product_id, currency_id, amount
             FROM prices
             WHERE product_id = :product_id AND currency_id = :currency_id
             LIMIT 1',
            [
                'product_id' => $productId,
                'currency_id' => $currencyId,
            ]
        );

        if ($rows === []) {
            return null;
        }

        return new self($rows[0]);
    }
}
