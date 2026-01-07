<?php

declare(strict_types=1);

namespace App\Util;

class StatusDespesaColor
{
    public static function getColor(\App\Enums\StatusDespesaEnum|string|int $status): string
    {
        if ($status instanceof \App\Enums\StatusDespesaEnum) {
            $status = $status->value;
        }

        if (is_int($status)) {
            $status = match ($status) {
                1 => 'pendente',
                2 => 'atrasado',
                3 => 'pago',
                default => '',
            };
        }

        return match (strtolower((string) $status)) {
            'pendente' => 'warning',
            'atrasado' => 'danger',
            'pago'     => 'success',
            default    => 'gray',
        };
    }
}
