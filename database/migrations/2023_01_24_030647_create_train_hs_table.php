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
        Schema::create('train_hs', function (Blueprint $table) {
            $table->id();
            $table->string('train_cod')->nullable();
            $table->string('train_dat')->nullable();
            $table->string('hari')->nullable();
            $table->string('jam')->nullable();
            $table->string('sdjam')->nullable();
            $table->string('tempat')->nullable();
            $table->string('pltran_cod')->nullable();
            $table->string('pltran_nam')->nullable();
            $table->string('train_tema')->nullable();
            $table->string('kompe_cod')->nullable();
            $table->string('kompetensi')->nullable();
            $table->string('pemateri')->nullable();
            $table->string('approve')->nullable();
            $table->string('tipe')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });


        DB::table('train_hs')->insert(
            ['train_cod' => 'TRA2301000'], 
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('train_hs');
    }
};
