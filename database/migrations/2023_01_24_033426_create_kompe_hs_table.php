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
        Schema::create('kompe_hs', function (Blueprint $table) {
            $table->id();
            $table->string('kompe_cod')->nullable();
            $table->string('bagian')->nullable();
            $table->string('tipe')->nullable()->default('Training');
            $table->timestamps();
        });

        DB::table('kompe_hs')->insert(
            ['kompe_cod' => 'KOD2301000'], 
        );
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kompe_hs');
    }
};
