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
        Schema::create('kompe_ds', function (Blueprint $table) {
            $table->id();
            $table->string('kompe_cod')->nullable();
            $table->unsignedBigInteger('kompe_id')->nullable();
            $table->string('kompetensi')->nullable();
            $table->string('jenis')->nullable();
            $table->timestamps();

            $table->foreign('kompe_id')->references('id')->on('kompe_hs')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kompe_ds');
    }
};
