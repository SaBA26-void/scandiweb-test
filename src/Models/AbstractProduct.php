<?php

namespace App\Models;

use App\Models\AbstractModel;

abstract class AbstractProduct extends AbstractModel
{
    public function __construct(
        private ?string $id = null,
        private ?string $name = null,
        private ?string $brand = null,
        private ?bool $isInStock = null,
        private ?string $decription = null,
        private ?string $categoryName = null,
        private ?array $productImages = null,
        private ?array $attributes = null,
        private ?array $price = null,
    ) {
        $this->id = $data['product_id'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->brand = $data['brand'] ?? '';
        $this->isInStock = $data['is_in_stock'] ?? false;
        $this->decription = $data['description'] ?? '';
        $this->categoryName = $data['category_name'] ?? '';
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

    public function getCategory(): string
    {
        return $this->categoryName;
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
            'description' => $this->decription,
            'category_name' => $this->categoryName,
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

    public static function findAll(?string $categoryName = null): array
    {
        $instance = new self();

        if ($categoryName !== null && $categoryName !== 'all') {
            $rows = $instance->db->query(
                'SELECT * FROM products WHERE category_name = :categroy_name',
                ['categroy_name' => $categoryName]
            );
        } else {
            $rows = $instance->db->query('SELECT * FROM products');
        }


        // temporary fix
        return [];
        //I need to join the data in order to return the complete
        // information
        // 
    }

    public static function findById(string  $id): ?self
    {
        $instance = new self();
        $rows = $instance->db->query(
            'SELECT * FROM products where id = :id ',
            ['id' => $id]
        );

        if (empty($rows)) return null;
        // temporary error fix
        return null;
        //I need to join the data in order to return the
        // complete information here too
        // 
    }
}
