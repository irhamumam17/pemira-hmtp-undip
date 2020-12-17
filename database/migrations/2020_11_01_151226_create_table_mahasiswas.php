<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMahasiswas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('name');
            $table->year('angkatan');
            $table->string('email');
            $table->string('password');
            $table->string('hint_password');
            $table->dateTime('start_session')->nullable();
            $table->dateTime('end_session')->nullable();
            $table->integer('status')->default(0);
            $table->text('auth_session')->nullable()->default(null)->comment('Stores the id of the user session');
            $table->dateTime('last_login')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mahasiswas');
    }
}
