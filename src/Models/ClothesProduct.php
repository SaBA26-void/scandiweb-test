<?php

namespace App\Models;

class ClothesProduct extends AbstractProduct
{
    public function getType(): string
    {
        return 'clothes';
    }
}
