<?php


namespace App\Models\Attributes;


use App\Models\AbstractModel;


class ProductAttributeValue extends AbstractModel
{
    private int $pavId;
    private string $productId;
    private int $attributeSetId;
    private string $attributeItemId;

    public function __construct(array $data = [])
    {
        parent::__construct();

        $this->pavId = (int) ($data['pav_id'] ?? 0);
        $this->productId = (string) ($data['product_id'] ?? '');
        $this->attributeSetId = (int) ($data['attribute_set_id'] ?? 0);
        $this->attributeItemId = (string) ($data['attribute_item_id'] ?? '');
    }

    public function getPavId(): int
    {
        return $this->pavId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getAttributeSetId(): int
    {
        return $this->attributeSetId;
    }

    public function getAttributeItemId(): string
    {
        return $this->attributeItemId;
    }

    public function toArray(): array
    {
        return [
            'pav_id' => $this->pavId,
            'product_id' => $this->productId,
            'attribute_set_id' => $this->attributeSetId,
            'attribute_item_id' => $this->attributeItemId,
        ];
    }


    public static function findByProductId(string $productId): array
    {
        $instance = new self();

        $rows = $instance->db->query(
            'SELECT pav_id, product_id, attribute_set_id, attribute_item_id
             FROM product_attribute_values
             WHERE product_id = :product_id
             ORDER BY pav_id',
            ['product_id' => $productId]
        );

        return array_map(
            static fn(array $row) => new self($row),
            $rows
        );
    }
}
