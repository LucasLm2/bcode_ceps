<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rua extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ruas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'bairro_id',
    ];

    public function bairro(): BelongsTo
    {
        return $this->belongsTo(Bairro::class);
    }

    public function ceps(): HasMany
    {
        return $this->hasMany(Cep::class);
    }
}
