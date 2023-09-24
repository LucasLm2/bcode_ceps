<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cep extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ceps';
    protected $primaryKey = 'cep';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cep',
        'regiao_id',
        'estado_id',
        'municipio_id',
        'bairro_id',
        'rua_id',
        'complemento',
    ];

    public function regiao(): BelongsTo
    {
        return $this->belongsTo(Regiao::class);
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    public function bairro(): BelongsTo
    {
        return $this->belongsTo(Bairro::class);
    }

    public function rua(): BelongsTo
    {
        return $this->belongsTo(Rua::class);
    }
}
