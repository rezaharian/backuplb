<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prob_msds', function (Blueprint $table) {
            $table->id();
            $table->string('id_no');
            $table->string('prob_cod');
            $table->string('tgl_input');
            $table->string('penyebab');
            $table->string('perbaikan');
            $table->string('tgl_rpr');
            $table->string('pencegahan');
            $table->string('tgl_pre');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prob_msds');
    }
};
