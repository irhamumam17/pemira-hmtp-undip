<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePaslons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paslons', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_urut');
            $table->unsignedBigInteger('ketua_mahasiswa_id');
            $table->unsignedBigInteger('wakil_mahasiswa_id');
            $table->text('visi');
            $table->text('misi');
            $table->string('foto');
            $table->integer('jumlah_suara')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('paslons',function($table){
            $table->foreign('ketua_mahasiswa_id')->references('id')->on('mahasiswas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('wakil_mahasiswa_id')->references('id')->on('mahasiswas')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paslons');
    }
}
