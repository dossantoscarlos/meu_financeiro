<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoricoDespesa extends Model
{
    protected $table = 'historico_despesas';

    protected $primaryKey = 'id';

    protected $fillable = [
        'despesa_id',
        'status_despesa_id',
        'data',
    ];

    public function despesa(): BelongsTo
    {
        return $this->belongsTo(Despesa::class);
    }

    public function status_despesa(): BelongsTo
    {
        return $this->belongsTo(StatusDespesa::class);
    }
}
