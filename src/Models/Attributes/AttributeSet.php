<?php

namespace App\Models\Attributes;

use App\Models\AbstractModel;

class AttributeSet extends AbstractModel
{
    private int $attributeSetId;
    private string $name;
    private string $type;

    public function __construct(array $data = [])
    {
        parent::__construct();

        $this->attributeSetId = (int) ($data['attribute_set_id'] ?? 0);
        $this->name = (string) ($data['name'] ?? '');
        $this->type = (string) ($data['type'] ?? '');
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
            'type' => $this->type,
        ];
    }


    public static function findAll(): array
    {
        $instance = new self();
        $rows = $instance->db->query(
            'SELECT attribute_set_id, name, type
             FROM attribute_sets
             ORDER BY attribute_set_id'
        );

        return array_map(
            static fn(array $row) => new self($row),
            $rows
        );
    }


    public static function findById(int $attributeSetId): ?self
    {
        $instance = new self();
        $rows = $instance->db->query(
            'SELECT attribute_set_id, name, type
             FROM attribute_sets
             WHERE attribute_set_id = :id
             LIMIT 1',
            ['id' => $attributeSetId]
        );

        if ($rows === []) {
            return null;
        }

        return new self($rows[0]);
    }
}
