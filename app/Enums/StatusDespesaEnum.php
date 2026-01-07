<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusDespesaEnum: int
{
    case PENDENTE = 1;
    case ATRASADO = 2;
    case PAGO = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDENTE => 'Pendente',
            self::ATRASADO => 'Atrasado',
            self::PAGO => 'Pago',
        };
    }
}
