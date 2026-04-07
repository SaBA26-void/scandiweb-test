<?php

namespace App\Models;

use App\Models\AbstractModel;

class AttributeItem extends AbstractModel
{
    private string $attributeItemId;
    private int $attributeSetId;
    private string $value;
    private string $displayValue;

    public function __construct(array $data = [])
    {
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
}
