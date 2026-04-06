<?php

namespace App\Models;

use App\Database\Connection;

class Category
{
    private readonly Connection $db;

    public function __construct(private ?int $id = null, private ?string $name = null)
    {
        $this->db = Connection::getInstance();
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
        $rows = $instance->db->query('SELECT id, name FROM categories ORDER BY id');

        return array_map(
            static fn(array $row)  => new self($row['id'], $row['title']),
            $rows,
        );
    }

    public static function findByName(string $name): ?self
    {
        $instance = new self();
        $rows = $instance->db->query('SELECT * FROM categories WHERE name = :name', [':name' => $name]);

        return !empty($rows) ? new self($rows[0]) : null;
    }
}
