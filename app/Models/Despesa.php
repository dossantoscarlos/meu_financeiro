<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Despesa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'despesas';

    protected $primaryKey = 'id';

    protected $fillable = [
        'status_despesa_id',
        'plano_id',
        'tipo_despesa_id',
        'descricao',
        'valor_documento',
        'data_vencimento',
    ];

    public function statusDespesa(): BelongsTo
    {
        return $this->belongsTo(StatusDespesa::class);
    }

    public function tipoDespesa(): BelongsTo
    {
        return $this->belongsTo(TipoDespesa::class);
    }

    public function plano(): BelongsTo
    {
        return $this->belongsTo(Plano::class);
    }

    public function historico(): HasMany
    {
        return $this->hasMany(HistoricoDespesa::class);
    }
}
