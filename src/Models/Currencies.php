<?php

namespace App\Models;

use App\Models\AbstractModel;

class Currencies extends AbstractModel
{
    private int $currencyId;
    private string $label;
    private string $symbole;

    public function __construct(array $data = [])
    {
        $this->currencyId = $data['currency_id'] ?? 0;
        $this->label = $data['label'] ?? '';
        $this->symbole = $data['sybol'] ?? '';
    }

    public function getCurrencyId(): int
    {
        return $this->currencyId;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSymbole(): string
    {
        return $this->symbole;
    }

    public function toArray(): array
    {
        return [
            'currency_id' => $this->currencyId,
            'label' => $this->label,
            'symbol' => $this->symbole
        ];
    }

    public static  function getCurrencies(): array
    {
        $instance = new self();

        $rows = $instance->db->query(
            "SELECT currency_id, label, symbol FROM currencies ORDER BY currency_id"
        );

        return array_map(
            static fn(array $row) => new self($row['currency_id'], $row['label'], $row['symbol']),
            $rows
        );
    }

    public static function getCurrencieByLabel(?string $label = null): ?array
    {
        $instance = new self();

        $rows = $instance->db->query(
            "SELECT currency_id, label, symbol FROM currencies where label = :label",
            ['label' => $label]
        );

        return !empty($rows) ?  array_map(
            static fn(array $row) => new self($row['lablel'], $row['currency_id'], $row['symbol']),
            $rows
        ) : null;
    }
}
