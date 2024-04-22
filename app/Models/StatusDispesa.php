<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusDispesa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'status_dispesas';

    protected $primaryKey = 'id';

    protected $fillable = ['nome'];

    public function dispesas(): HasMany
    {
        return $this->hasMany(Dispesa::class);
    }
}
