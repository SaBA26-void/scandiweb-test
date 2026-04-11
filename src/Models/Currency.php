<?php


namespace App\Models;


class Currency extends AbstractModel
{

    private int $currencyId;
    private string $label;
    private string $symbol;


    public function __construct(array $data = [])
    {
        parent::__construct();

        $this->currencyId = (int) ($data['currency_id'] ?? 0);
        $this->label = (string) ($data['label'] ?? '');
        $this->symbol = (string) ($data['symbol'] ?? '');
    }


    public function getCurrencyId(): int
    {
        return $this->currencyId;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }


    public function toArray(): array
    {
        return [
            'currency_id' => $this->currencyId,
            'label' => $this->label,
            'symbol' => $this->symbol,
        ];
    }



    public static function findAll(): array
    {
        $instance = new self();
        $rows = $instance->db->query(
            'SELECT currency_id, label, symbol FROM currencies ORDER BY currency_id'
        );

        return array_map(
            static fn(array $row) => new self($row),
            $rows
        );
    }

    public static function findByLabel(string $label): ?self
    {
        $instance = new self();
        $rows = $instance->db->query(
            'SELECT currency_id, label, symbol FROM currencies WHERE label = :label LIMIT 1',
            ['label' => $label]
        );

        if ($rows === []) {
            return null;
        }

        return new self($rows[0]);
    }
}
