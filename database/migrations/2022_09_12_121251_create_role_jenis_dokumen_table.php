<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleJenisDokumenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_jenis_dokumen', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->constrained('roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('jenis_dokumen_id')
                ->constrained('jenis_dokumen')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->boolean('view');
            $table->boolean('upload');
            $table->boolean('download');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_jenis_dokumen');
    }
}
