<?php

namespace Database\Seeders;

use App\Models\Bairro;
use App\Models\Cep;
use App\Models\Rua;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class ImportCepsSeeder extends Seeder
{
    private $contadorBairro = 1;
    private $contadorRua = 1;
    private $bairros;
    private $ruas;
    private $ceps;

    public function __construct(
        private string $sigla,
        private int $regiaoId,
        private int $estadoId
    ) { }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siglaLower = strtolower($this->sigla);
        $arquivos = glob(database_path("seeders/imports/ceps/{$this->sigla}/*.*"));
        if (count($arquivos) > 0) {
            $qtdArquivos = count($arquivos);
            for ($i = 1; $i <= $qtdArquivos; $i++) {
                $inicio = microtime(true);                    
                $handle = fopen(database_path("seeders/imports/ceps/{$this->sigla}/{$siglaLower}.parte_{$i}.csv"), 'r');

                if ($handle !== false) {
                    $this->ceps = [];
                    $this->bairros = [];
                    $this->ruas = [];

                    while (($line = fgetcsv($handle, 1000, ',')) !== false) {
                        list($cep, $rua, $complemento, $bairro, $municipioId) = $line;
                        $bairroId = $municipioId == null || $bairro == null ? null : $this->getBairro($bairro, $municipioId);
                        $ruaId = $rua == null ? null : $this->getRua($rua, $bairroId);
                        $this->ceps[] = [
                            'cep' => $cep,
                            'regiao_id' => $this->regiaoId,
                            'estado_id' => $this->estadoId,
                            'municipio_id' => $municipioId,
                            'bairro_id' => $bairroId,
                            'rua_id' => $ruaId,
                            'complemento' => $complemento ?? null,
                        ];
                    }

                    $this->criarBairro();
                    $this->criarRua();
                    $this->criarCep();
                }

                fclose($handle);

                $tempo = microtime(true) - $inicio;
                print_r(['UF' => $this->sigla,'Quantidade' => count($this->ceps), 'Tempo' => $tempo]);
            }
        }
    }

    private function getBairro(string $nome, int $municipioId): int
    {
        return Cache::remember('bairro-'.$nome, now()->addDay(), function() use ($nome, $municipioId) {
            $id = $this->contadorBairro;
            $this->bairros[] = ['id' => $id, 'nome' => $nome, 'municipio_id' => $municipioId];
            $this->contadorBairro++;
            return $id;
        });
    }

    private function getRua(string $nome, ?int $bairroId): int
    {
        return Cache::remember('rua-'.$nome, now()->addDay(), function() use ($nome, $bairroId) {
            $id = $this->contadorRua;
            $this->ruas[] = ['id' => $this->contadorRua, 'nome' => $nome, 'bairro_id' => $bairroId];
            $this->contadorRua++;
            return $id;
        });
    }

    private function criarBairro(): void
    {
        $multiplosBairros = array_chunk($this->bairros, 4000);
        foreach ($multiplosBairros as $bairros) {
            Bairro::upsert(
                $bairros,
                ['id'],
                ['nome', 'municipio_id']
            );
        }
    }

    private function criarRua(): void
    {
        $multiplasRuas = array_chunk($this->ruas, 4000);
        foreach ($multiplasRuas as $ruas) {
            Rua::upsert(
                $ruas,
                ['id'],
                ['nome', 'bairro_id']
            );
        }
    }

    private function criarCep(): void
    {
        $multiplosCeps = array_chunk($this->ceps, 4000);
        foreach ($multiplosCeps as $ceps) {
            Cep::upsert(
                $ceps,
                ['cep'],
                ['regiao_id', 'estado_id', 'municipio_id', 'bairro_id', 'rua_id', 'complemento']
            );
        }
    }
}
