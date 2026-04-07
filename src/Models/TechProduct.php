<?php

namespace App\Models;

class TechProduct extends AbstractProduct
{
    public function getType(): string
    {
        return 'tech';
    }
}
