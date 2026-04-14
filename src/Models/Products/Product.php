<?php

namespace App\Models\Products;

use App\Models\AbstractModel;

class Product extends AbstractModel
{
    private string $id;
    private string $name;
    private string $brand;
    private bool $inStock;
    private string $description;
    private string $category;

    public function __construct(array $data = [])
    {
        parent::__construct();

        $this->id = (string)($data['product_id'] ?? '');
        $this->name = (string)($data['name'] ?? '');
        $this->brand = (string)($data['brand'] ?? '');
        $this->inStock = (bool)($data['is_in_stock'] ?? false);
        $this->description = (string)($data['description'] ?? '');
        $this->category = (string)($data['category_name'] ?? '');
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getBrand(): string
    {
        return $this->brand;
    }
    public function isInStock(): bool
    {
        return $this->inStock;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getCategoryName(): string
    {
        return $this->category;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand,
            'inStock' => $this->inStock,
            'description' => $this->description,
            'category' => $this->category,
        ];
    }

    public static function findAll(?string $category = null): array
    {
        $instance = new self();

        $sql = 'SELECT product_id, name, brand, is_in_stock, description, category_name FROM products';
        $params = [];

        if ($category !== null && $category !== '' && $category !== 'all') {
            $sql .= ' WHERE category_name = :category_name';
            $params['category_name'] = $category;
        }

        $sql .= ' ORDER BY name';

        $rows = $instance->db->query($sql, $params);

        return array_map(static fn(array $row) => new self($row), $rows);
    }

    public static function findById(string $id): ?self
    {
        $instance = new self();
        $rows = $instance->db->query(
            'SELECT product_id, name, brand, is_in_stock, description, category_name
             FROM products
             WHERE product_id = :id
             LIMIT 1',
            ['id' => $id]
        );

        return $rows ? new self($rows[0]) : null;
    }
}
