<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plano extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'planos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'descricao_simples',
        'mes_ano',
        'user_id',
    ];

    public function despesas(): HasMany
    {
        return $this->hasMany(Despesa::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gastos(): HasOne
    {
        return $this->hasOne(Gasto::class);
    }
}
