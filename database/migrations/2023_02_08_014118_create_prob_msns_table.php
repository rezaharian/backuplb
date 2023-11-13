<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('prob_msns', function (Blueprint $table) {
            $table->id();
            $table->string('prob_cod');
            $table->string('tgl_input')->nullable();
            $table->string('line')->nullable();
            $table->string('unitmesin')->nullable();
            $table->string('masalah')->nullable();
            $table->string('img_pro01')->nullable();
            $table->string('img_pro02')->nullable();
            $table->string('img_pro03')->nullable();
            $table->string('img_pro04')->nullable();

            $table->timestamps();
        });

        DB::table('prob_msns')->insert(
            ['prob_cod' => '20230001'], 
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prob_msns');
    }
};
