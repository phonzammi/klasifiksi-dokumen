<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataHasilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_hasil', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokumen');
            $table->foreignId('jenis_dokumen_id')
                ->constrained('jenis_dokumen')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('nilai_kemiripan');
            $table->foreignId('data_training_id')
                ->constrained('data_training')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_hasil');
    }
}
