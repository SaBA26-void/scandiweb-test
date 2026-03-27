<?php

namespace App\Models;

use App\Database\Connection;

class Category
{
    protected Connection $db;
    private int $id;
    private string $name;

    public function __construct(array $data = [])
    {
        $this->db = Connection::getInstance();
        $this->id = (int) ($data['id'] ?? 0);
        $this->name = $data['name'] ?? '';
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
            static fn(array $row)  => new self($row),
            $rows
        );
    }

    public static function findByName(string $name): ?self
    {
        $instance = new self();
        $rows = $instance->db->query('SELECT * FROM categories WHERE name = :sstring', [$name]);

        return !empty($rows) ? new self($rows[0]) : null;
    }
}
