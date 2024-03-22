<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receita extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="receitas";

    protected $primaryKey="id";

    protected $fillable = array('saldo','custo', 'user_id');


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    } 

}
