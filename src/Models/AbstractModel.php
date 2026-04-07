<?php

namespace App\Models;


use App\Database\Connection;


abstract class AbstractModel
{
    protected Connection $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    abstract public function toArray(): array;
}
