<?php

use App\Models\Municipio;
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
        Schema::create('bairros', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('nome', 50);
            $table->unsignedSmallInteger('municipio_id');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('municipio_id')->references('id')->on('municipios');

            // Indexs
            $table->index('nome');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bairros');
    }
};
