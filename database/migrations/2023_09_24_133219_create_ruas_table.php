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
        Schema::create('ruas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->unsignedMediumInteger('bairro_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('bairro_id')->references('id')->on('bairros');
            // Indexs
            $table->index('nome');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruas');
    }
};
