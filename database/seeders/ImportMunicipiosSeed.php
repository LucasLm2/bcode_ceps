<?php

namespace Database\Seeders;

use App\Models\Municipio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportMunicipiosSeed extends Seeder
{
    protected array $municipios = [];

    /**
     * Run the database seeds.
     */
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
        $handle = fopen(database_path('seeders/imports/municipios.csv'), 'r');

        if ($handle !== false) {
            while (($line = fgetcsv($handle, 1000, ',')) !== false) {
                list($id, $nome, $estadoId) = $line;

                $this->municipios[] = [
                    'id' => $id,
                    'nome' => $nome,
                    'estado_id' => $estadoId,
                ];
            }
            fclose($handle);
        }


    }

    protected function persist(): void
    {
        DB::transaction(function(){
           foreach ($this->municipios as $cidade) {
               $id = $cidade['id'];
               unset($cidade['id']);
               Municipio::updateOrCreate(
                   ['id' => $id],
                   $cidade
               );
           }
        });
    }
}
