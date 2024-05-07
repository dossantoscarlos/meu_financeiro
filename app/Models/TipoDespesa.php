<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDespesa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tipo_despesas';

    protected $primaryKey = 'id';

    protected $fillable = ['nome'];

    protected function nome(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): ?string => $value,
            set: fn (string $value): string => trim(strtoupper($value)));
    }

    public function despesas(): HasMany
    {
        return $this->hasMany(Despesa::class);
    }
}
