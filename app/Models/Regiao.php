<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regiao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'regioes';

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

    public function estados(): HasMany
    {
        return $this->hasMany(Estado::class);
    }

    public function ceps(): HasMany
    {
        return $this->hasMany(Cep::class);
    }
}
