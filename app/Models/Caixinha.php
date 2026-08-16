<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $descricao
 * @property float $valor_produto
 * @property int $parcelas
 * @property string $valor_parcela
 * @property int|null $user_id
 */
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
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    private function parcelaCaixinha(mixed $value = null): float
    {
        return floatval($this->valor_produto) / floatval($this->parcelas);
    }

    protected function valorParcela(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): string => number_format(floatval($value), 2, ',', '.'),
            set: $this->parcelaCaixinha(...)
        );
    }
}
