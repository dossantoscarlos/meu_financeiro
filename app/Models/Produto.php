<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];
}
