<?php

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
        Schema::create('municipios', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedMediumInteger('cod_ibge')->nullable();
            $table->string('nome', 100);
            $table->unsignedTinyInteger('estado_id');
            $table->unsignedTinyInteger('ddd')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('estado_id')->references('id')->on('estados');

            // Indexs
            $table->index('cod_ibge');
            $table->index('nome');
            $table->index('ddd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};
