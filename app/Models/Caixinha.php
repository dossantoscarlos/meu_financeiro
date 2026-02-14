<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caixinha extends Model
{
    /** @use HasFactory<\Database\Factories\CaixinhaFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'caixinhas';

    protected $primaryKey = 'id';

    protected $fillable = [
        'descricao',
        'valor_produto',
        'parcelas',
        'valor_parcela',
    ];

    private function parcelaCaixinha(): float
    {
        return floatval($this->valor_produto) / floatval($this->parcelas);
    }

    protected function valorParcela(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): ?string => number_format(floatval($value), 2, ',', '.'),
            set: $this->parcelaCaixinha(...)
        );
    }

    public function getDeletedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d/m/Y H:i:s') : null;
    }
}
