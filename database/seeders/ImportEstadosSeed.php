<?php

namespace Database\Seeders;

use App\Library\Ibge;
use App\Models\Estado;
use App\Models\Regiao;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ImportEstadosSeed extends Seeder
{
    /**
     * Run the database seeds.
     */

    /**
     * @var array
     */
    protected array $estados;

    public function run(): void
    {
        $this->read();
        $this->persist();
    }

    /**
     * Read the file and mount date into array
     * @return void
     */
    protected function read(): void
    {
        $this->estados = Ibge::estados()->map(function($estado){
            $regiaoId = $this->getRegionByCodeIbge($estado['regiao']['id'])->id;
            return  [
                'cod_ibge' => $estado['id'],
                'sigla' => $estado['sigla'],
                'nome' => $estado['nome'],
                'regiao_id' => $regiaoId,
            ];
        })->toArray();
    }

    /**
     * Persist the date into a database
     * @return void
     */
    protected function persist(): void
    {
        DB::transaction(function () {
            foreach($this->estados as $estado) {
                $codeIbge = $estado['cod_ibge'];
                Estado::updateOrCreate(
                    ['cod_ibge' => $codeIbge],
                    $estado
                );
            }
        });
    }

    protected function getRegionByCodeIbge($codeIbge)
    {
        return Cache::remember('region-'.$codeIbge, now()->addMinute(), function () use ($codeIbge) {
            return Regiao::select('id')->where('cod_ibge', $codeIbge)->first();
        });
    }
}
