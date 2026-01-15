<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusDespesa extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const PENDENTE = 1;
    public const ATRASADO = 2;
    public const PAGO = 3;

    protected $table = 'status_despesas';

    protected $primaryKey = 'id';

    protected $fillable = ['nome'];

    public function historico(): HasMany
    {
        return $this->hasMany(HistoricoDespesa::class);
    }
}
