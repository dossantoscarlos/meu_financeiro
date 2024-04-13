<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TipoDispesa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tipo_dispesas';

    protected $primaryKey = 'id';

    protected $fillable = ['nome'];

    protected function nome() : Attribute 
    {
        return Attribute::make(
            get:fn(?string $value) : ?string => $value,
            set:fn(string $value) : string => trim(strtoupper($value)));
    }

    public function dispesa(): HasMany
    {
        return $this->hasMany(Dispesa::class);
    }
}
