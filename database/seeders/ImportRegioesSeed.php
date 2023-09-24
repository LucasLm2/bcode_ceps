<?php

namespace Database\Seeders;

use App\Library\Ibge;
use App\Models\Regiao;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportRegioesSeed extends Seeder
{
    protected array $regioes = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->read();
        $this->persist();
    }

    protected function read(): void
    {
        $this->regioes = Ibge::regioes()->map(function($regiao) {
            return [
                'cod_ibge' => $regiao['id'],
                'sigla' => $regiao['sigla'],
                'nome' => $regiao['nome']
            ];
        })->toArray();
    }

    protected function persist(): void
    {
        DB::transaction(function() {
            foreach ($this->regioes as $regiao) {
                Regiao::updateOrCreate(
                    ['cod_ibge' => $regiao['cod_ibge']],
                    $regiao
                );
            }
        });
    }
}
