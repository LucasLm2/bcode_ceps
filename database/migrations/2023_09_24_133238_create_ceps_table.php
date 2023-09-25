<?php

use App\Models\Rua;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ceps', function (Blueprint $table) {
            $table->string('cep', 8);
            $table->unsignedTinyInteger('regiao_id');
            $table->unsignedTinyInteger('estado_id');
            $table->unsignedSmallInteger('municipio_id');
            $table->unsignedMediumInteger('bairro_id')->nullable();
            $table->foreignIdFor(Rua::class, 'rua_id')->nullable()->constrained();
            $table->string('complemento', 100)->nullable();
            $table->string('latitude', 20)->nullable();
            $table->string('longitude', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('regiao_id')->references('id')->on('regioes');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->foreign('municipio_id')->references('id')->on('municipios');
            $table->foreign('bairro_id')->references('id')->on('bairros');

            // Indexs
            $table->primary('cep');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ceps');
    }
};
