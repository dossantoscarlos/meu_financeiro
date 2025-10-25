<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'produtos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'descricao_curta',
        'preco',
        'quantidade',
        'tipo_medida',
        'data_compra',
        'user_id',
        'total',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    private function totalProduto(): float
    {
        return floatval($this->preco) * floatval($this->quantidade);
    }

    protected function total(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): ?string => $value,
            set: $this->totalProduto(...)
        );
    }
}
