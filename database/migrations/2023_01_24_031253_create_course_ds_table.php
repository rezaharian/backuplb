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
        Schema::create('course_ds', function (Blueprint $table) {
            $table->id();
            $table->string('train_cod')->nullable();
            $table->string('no_payroll')->nullable();
            $table->string('no_reg')->nullable();
            $table->string('course_id')->nullable();
            $table->string('course_nam')->nullable();
            $table->string('tanggal')->nullable();
            $table->string('tahun')->nullable();
            $table->string('nilai')->nullable();
            $table->string('nilai_kom')->nullable();
            $table->string('jam')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('tipe')->nullable();
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
        Schema::dropIfExists('course_ds');
    }
};
