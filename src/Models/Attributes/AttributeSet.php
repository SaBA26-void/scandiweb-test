<?php

namespace App\Models;

use App\Models\AbstractModel;

class AttributeSet extends AbstractModel
{
    private int $attributeSetId;
    private string $name;
    private  string $type;

    public function __construct(array $data = [])
    {
        $this->attributeSetId = (int) $data['attribute_set_id'] ?? 0;
        $this->name = (string) $data['name'] ?? '';
        $this->type = (string) $data['type'] ?? '';
    }

    public function getAttributeSetId(): int
    {
        return $this->attributeSetId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function toArray(): array
    {
        return [
            'attribute_set_id' => $this->attributeSetId,
            'name' => $this->name,
            'type' => $this->type
        ];
    }
}
