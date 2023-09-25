<?php

namespace Database\Seeders;

use App\Jobs\ProcessSeeder;
use App\Models\Estado;
use Illuminate\Database\Seeder;

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
            ProcessSeeder::dispatch(new ImportCepsSeeder($estado->sigla, $estado->regiao_id, $estado->id));
        }
    }
}
