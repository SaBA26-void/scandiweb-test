<?php


namespace App\Models;



class Category extends AbstractModel
{
    public function __construct(private int $id = 0, private string $name = '')
    {
        parent::__construct();
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }


    public static function findAll(): array
    {
        $instance = new self();
        $rows = $instance->db->query(
            'SELECT category_id, name FROM categories ORDER BY category_id'
        );

        return array_map(
            static fn(array $row) => new self((int) $row['category_id'], (string) $row['name']),
            $rows
        );
    }

    public static function findByName(string $name): ?self
    {
        $instance = new self();
        $rows = $instance->db->query(
            'SELECT category_id, name FROM categories WHERE name = :name LIMIT 1',
            ['name' => $name]
        );

        if ($rows === []) {
            return null;
        }

        return new self((int) $rows[0]['category_id'], (string) $rows[0]['name']);
    }
}
