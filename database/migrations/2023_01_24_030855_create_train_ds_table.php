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
        Schema::create('train_ds', function (Blueprint $table) {
            $table->id();
            $table->string('train_cod')->nullable();
            $table->string('train_dat')->nullable();
            $table->string('no_payroll')->nullable();
            $table->string('nama_asli')->nullable();
            $table->string('nilai_pre')->nullable();
            $table->string('nilai')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('approve')->nullable();
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
        Schema::dropIfExists('train_ds');
    }
};
