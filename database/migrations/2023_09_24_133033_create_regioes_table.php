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
        Schema::create('regioes', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->unsignedTinyInteger('cod_ibge')->nullable();
            $table->string('sigla', 2);
            $table->string('nome', 12);
            $table->timestamps();
            $table->softDeletes();

            // Indexs
            $table->index('cod_ibge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regioes');
    }
};
