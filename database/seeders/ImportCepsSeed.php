<?php

namespace Database\Seeders;

use App\Models\Bairro;
use App\Models\Cep;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Rua;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class ImportCepsSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->read();
    }

    protected function read(): void
    {
        $estados = Estado::select('sigla', 'id', 'regiao_id')->get();

        foreach ($estados as $estado) {
            info($estado->sigla);
            $sigla = strtolower($estado->sigla);

            $arquivos = glob(database_path("seeders/imports/ceps/{$estado->sigla}/*.*"));
            if (count($arquivos) > 0) {
                $qtdArquivos = count($arquivos);
                for ($i = 1; $i <= $qtdArquivos; $i++) { 
                    $handle = fopen(database_path("seeders/imports/ceps/{$estado->sigla}/{$sigla}.parte_{$i}.csv"), 'r');
    
                    if ($handle !== false) {
                        while (($line = fgetcsv($handle, 1000, ',')) !== false) {
                            list($cep, $rua, $complemento, $bairro, $municipioId) = $line;
                            $municipio = $this->getMunicipio($municipioId);
                            $bairro = $this->criarAtualizarBairro($bairro, $municipio->id);
                            $rua = $this->createRua($rua, $bairro->id);
                            $this->criarCep([
                                'cep' => $cep,
                                'regiao_id' => $estado->regiao_id,
                                'estado_id' => $estado->id,
                                'municipio_id' => $municipio->id,
                                'bairro_id' => $bairro->id,
                                'rua_id' => $rua->id,
                                'complemento' => $complemento ?? null,
                            ]);
                        }
                    }
    
                    fclose($handle);
                }
            }
        }
    }

    protected function getMunicipio($municipioId)
    {
        return Cache::remember('municipio-'.$municipioId, now()->addMinutes(5), function() use ($municipioId) {
            return Municipio::findOrFail($municipioId);
        });
    }

    protected function criarAtualizarBairro(string $nome, $municipioId)
    {
        $bairro = Cache::remember('bairro-'.$nome, now()->addDay(), function() use ($nome, $municipioId) {
            return Bairro::where('nome', $nome)->where('municipio_id', $municipioId)->first();
        });

        if ($bairro) {
            return $bairro;
        }

        return Bairro::create(
            ['nome' => $nome, 'municipio_id' => $municipioId]
        );
    }

    protected function createRua(string $rua, $bairroId)
    {
        return Rua::create([
            'nome' => $rua, 'bairro_id' => $bairroId
        ]);
    }

    protected function criarCep(array $attributes): void
    {
        Cep::create($attributes);
    }
}
