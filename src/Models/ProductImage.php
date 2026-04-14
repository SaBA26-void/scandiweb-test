<?php


namespace App\Models;


class ProductImage extends AbstractModel
{

    private int $productImageId;
    private string $productId;
    private string $productImageUrl;


    public function __construct(array $data = [])
    {
        parent::__construct();

        $this->productImageId = (int) ($data['product_image_id'] ?? 0);
        $this->productId = (string) ($data['product_id'] ?? '');
        $this->productImageUrl = (string) ($data['product_image_url'] ?? '');
    }


    public function getProductImageId(): int
    {
        return $this->productImageId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getProductImageUrl(): string
    {
        return $this->productImageUrl;
    }


    public function toArray(): array
    {
        return [
            'product_image_id' => $this->productImageId,
            'product_id' => $this->productId,
            'product_image_url' => $this->productImageUrl,
        ];
    }



    public static function findByProductId(string $productId): array
    {
        if ($productId === '') {
            return [];
        }

        $instance = new self();
        $rows = $instance->db->query(
            'SELECT product_image_id, product_id, product_image_url
             FROM product_images
             WHERE product_id = :product_id
             ORDER BY product_image_id',
            ['product_id' => $productId]
        );

        return array_map(
            static fn(array $row) => new self($row),
            $rows
        );
    }

    public static function findByImageId(int $productImageId): array
    {
        $instance = new self();
        $rows = $instance->db->query(
            'SELECT product_image_id, product_id, product_image_url
             FROM product_images
             WHERE product_image_id = :product_image_id',
            ['product_image_id' => $productImageId]
        );

        return array_map(
            static fn(array $row) => new self($row),
            $rows
        );
    }
}
