<?php


namespace App\Models\Products;


use App\Models\AbstractModel;


abstract class AbstractProduct extends AbstractModel
{
    protected string $id;
    protected string $name;
    protected string $brand;
    protected bool $isInStock;
    protected string $description;
    protected string $categoryName;
    protected array $productImages;
    protected array $attributes;
    protected array $prices;

    public function __construct(array $data = [])
    {
        parent::__construct();

        $this->id = (string) ($data['product_id'] ?? '');
        $this->name = (string) ($data['name'] ?? '');
        $this->brand = (string) ($data['brand'] ?? '');
        $this->isInStock = !empty($data['is_in_stock']);
        $this->description = (string) ($data['description'] ?? '');
        $this->categoryName = (string) ($data['category_name'] ?? '');
        $this->productImages = $data['product_images'] ?? [];
        $this->attributes = $data['product_attribute_values'] ?? [];
        $this->prices = $data['prices'] ?? [];
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
        return $this->description;
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
        return $this->prices;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_in_stock' => $this->isInStock,
            'description' => $this->description,
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
                $this->prices
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
            ),
        ];
    }
}
