<?php

namespace App\Library;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Ibge
{

    public static function regioes(): Collection
    {
        $response = Http::get('http://servicodados.ibge.gov.br/api/v1/localidades/regioes');

        return collect($response->json());

    }

    public static function estados(): Collection
    {
        $response = Http::get('http://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome');
        return collect($response->json());
    }

    public static function cidades(string $uf): Collection
    {
        $response = Http::get("http://servicodados.ibge.gov.br/api/v1/localidades/estados/{$uf}/municipios");
        return collect($response->json());
    }

    public static function municipios(){
        $response = Http::get("http://servicodados.ibge.gov.br/api/v1/localidades/municipios");
        return collect($response->json());
    }

}
