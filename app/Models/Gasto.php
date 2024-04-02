<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gasto extends Model
{
    use HasFactory;

    protected $table = 'gastos';

    protected $primaryKey = 'id';

    protected $fillable = ['plano_id', 'valor'];

    public function plano(): BelongsTo
    {
        return $this->belongsTo(Plano::class);
    }
}
