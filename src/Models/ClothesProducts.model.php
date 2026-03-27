<?php

namespace App\Models;

use App\Database\Connection;
use SplStack;

class ClothesProducts
{
    private string $id;
    private string $name;
    private string $brand;
    private bool $isInStock;
    private string $decription;
    private int $categoryId;
    private Connection $db;
    private array $productImages  = [];
    private array $attributes = [];
    private array $price = [];

    public function __construct(array $data = [])
    {
        $this->db = Connection::getInstance();
        $this->id = $data['product_id'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->brand = $data['brand'] ?? '';
        $this->isInStock = $data['is_in_stock'] ?? false;
        $this->decription = $data['description'] ?? '';
        $this->categoryId = $data['category_id'] ?? 0;
        $this->productImages = $data['product_images'] ?? [];
        $this->attributes = $data['product_attribute_values'] ?? [];
        $this->price = $data['prices'] ?? [];
    }

    public function getProductId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isInStock(): bool
    {
        return $this->isInStock;
    }

    public function getDescription(): string
    {
        return $this->decription;
    }

    public function getCategory(): int
    {
        return $this->categoryId;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getProductImages(): array
    {
        return $this->productImages;
    }

    public function getPrice(): array
    {
        return $this->price;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_in_stock' => $this->isInStock,
            'decription' => $this->decription,
            'categroy_id' => $this->categoryId,
            'brand' => $this->brand,
            'product_images' => array_map(
                static fn(array $img) => $img['product_image_url'],
                $this->productImages
            ),
            'price' => array_map(
                static fn(array $p) => [
                    'amount' => (float) $p['amount'],
                    'currency' => [
                        'label' => $p['currency_label'],
                        'symbol' => $p['currency_symbol'],
                    ],
                ],
                $this->price
            ),
            'attributes' => array_map(
                static fn(array $a) => [
                    'set' => $a['attribute_set'],
                    'type' => $a['attribute_type'],
                    'item_id' => $a['attribute_item_id'],
                    'value' => $a['value'],
                    'display_value' => $a['display_value'],
                ],
                $this->attributes
            )
        ];
    }
}
