<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePemilihanTemps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemilihan_temps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('paslons_id');
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('pemilihan_temps',function($table){
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('paslons_id')->references('id')->on('paslons')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemilihan_temps');
    }
}
