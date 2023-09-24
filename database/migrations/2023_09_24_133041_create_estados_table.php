<?php

use App\Models\Regiao;
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
        Schema::create('estados', function (Blueprint $table) {
            $table->tinyIncrements('id');;
            $table->unsignedTinyInteger('cod_ibge')->nullable();
            $table->string('sigla', 2);
            $table->string('nome', 20);
            $table->unsignedTinyInteger('regiao_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('regiao_id')->references('id')->on('regioes');

            // Indexs
            $table->index('cod_ibge');
            $table->unique('sigla');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados');
    }
};
