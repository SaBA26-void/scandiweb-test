<?php

namespace App\Models;

use App\Database\Connection;

class ProductImages
{
    private int $producImageId;
    private string $productId;
    private string $productImageUrl;
    private readonly Connection $db;

    public function __construct(array $data = [])
    {
        $this->db = Connection::getInstance();
        $this->producImageId = (int) ($data['product_image_id'] ?? 0);
        $this->productId = $data['product_id'] ?? '';
        $this->productImageUrl = $data['product_image_url'] ?? '';
    }

    public function getProductImageId(): int
    {
        return $this->producImageId;
    }

    public function toArray(): array
    {
        return [
            'product_image_id' => $this->producImageId,
            'product_id' => $this->productId,
            'product_image_url' => $this->productImageUrl
        ];
    }

    public static function getProductImages(string $productId): array
    {
        $instance = new self();

        if (!empty($productId)) {
            $rows = $instance->db->query(
                'SELECT product_image_id, product_image_url FROM product_images WHERE product_id = :product_id',
                ['product_id' => $productId]
            );

            return array_map(
                static fn(array $row) => new self($row),
                $rows
            );
        }

        return [];
    }
}
