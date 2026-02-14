<?php

declare(strict_types=1);

namespace App\Enum;

enum StatusDespesaEnum: int
{
    case PENDENTE = 1;
    case ATRASADO = 2;
    case PAGO     = 3;
}
