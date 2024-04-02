<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispesa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'dispesas';

    protected $primaryKey = 'id';

    protected $fillable = [
        'status_dispesa_id',
        'plano_id',
        'tipo_dispesa_id',
        'descricao',
        'valor_documento',
        'data_vencimento',
    ];

    public function statusDispesa(): BelongsTo
    {
        return $this->belongsTo(StatusDispesa::class);
    }

    public function tipoDispesa(): BelongsTo
    {
        return $this->belongsTo(TipoDispesa::class);
    }

    public function plano(): BelongsTo
    {
        return $this->belongsTo(Plano::class);
    }
}
