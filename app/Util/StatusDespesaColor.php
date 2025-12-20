<?php

declare(strict_types=1);

namespace App\Util;

class StatusDespesaColor
{
    public static function getColor(string $status): string
    {
        return match ($status) {
            'pendente' => 'warning',
            'pago' => 'success',
            'atrasado' => 'danger',
            default => 'gray',
        };
    }
}
