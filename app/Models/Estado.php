<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'estados';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cod_ibge',
        'sigla',
        'nome',
    ];

    public function regiao(): BelongsTo
    {
        return $this->belongsTo(Regiao::class);
    }

    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class);
    }

    public function ceps(): HasMany
    {
        return $this->hasMany(Cep::class);
    }
}
