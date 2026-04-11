<?php

namespace App\Models\Attributes;

use App\Models\AbstractModel;

class AttributeItem extends AbstractModel
{
    private string $attributeItemId;
    private int $attributeSetId;
    private string $value;
    private string $displayValue;

    public function __construct(array $data = [])
    {
        parent::__construct();

        $this->attributeItemId = (string) ($data['attribute_item_id'] ?? '');
        $this->attributeSetId = (int) ($data['attribute_set_id'] ?? 0);
        $this->value = (string) ($data['value'] ?? '');
        $this->displayValue = (string) ($data['display_value'] ?? '');
    }

    public function getAttributeItemId(): string
    {
        return $this->attributeItemId;
    }

    public function getAttributeSetId(): int
    {
        return $this->attributeSetId;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDisplayValue(): string
    {
        return $this->displayValue;
    }

    public function toArray(): array
    {
        return [
            'attribute_item_id' => $this->attributeItemId,
            'attribute_set_id' => $this->attributeSetId,
            'value' => $this->value,
            'display_value' => $this->displayValue,
        ];
    }


    public static function getItems(): array
    {
        $instance = new self();
        $rows = $instance->db->query(
            'SELECT attribute_item_id, attribute_set_id, value, display_value
             FROM attribute_items
             ORDER BY attribute_set_id, attribute_item_id'
        );

        return array_map(
            static fn(array $row) => new self($row),
            $rows
        );
    }

    public static function findByAttributeSetId(int $attributeSetId): array
    {
        $instance = new self();
        $rows = $instance->db->query(
            'SELECT attribute_item_id, attribute_set_id, value, display_value
             FROM attribute_items
             WHERE attribute_set_id = :attribute_set_id
             ORDER BY attribute_item_id',
            ['attribute_set_id' => $attributeSetId]
        );

        return array_map(
            static fn(array $row) => new self($row),
            $rows
        );
    }
}
