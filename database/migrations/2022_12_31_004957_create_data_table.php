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
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal')->nullable()->default(now()->format('Y-m-d'));
            $table->string('reset')->default('Null')->nullable();
            $table->integer('awal')->default(0)->nullable();
            $table->integer('akhir')->default(0)->nullable();
            $table->integer('kalkulasi')->default(0)->nullable();;
            $table->integer('jumlahpershift')->default(0)->nullable();
            $table->integer('jumlahperhari')->default(0)->nullable();
            $table->integer('awaljam')->default(0)->nullable();
            $table->integer('awalmenit')->default(0)->nullable();
            $table->integer('akhirjam')->default(0)->nullable();
            $table->integer('akhirmenit')->default(0)->nullable();
            $table->integer('runtimemenit')->default(0)->nullable();
            $table->string('etc')->default(0)->nullable();
            $table->string('jalur_id')->default(1)->nullable();
            $table->string('shift')->default('Null')->nullable();
            $table->integer('runtimeshift')->default(0)->nullable();
            $table->integer('runtimehari')->default(0)->nullable();
            $table->string('spk')->default(0)->nullable();
            $table->string('produk')->default(0)->nullable();
            $table->string('keterangan')->default(0)->nullable();
            $table->string('operator')->default(0)->nullable();
            $table->string('resetw')->default('Null')->nullable();

            $table->timestamps();
        });
        

        
        DB::table('data')->insert(
            ['jalur_id' => 1 ],
        );
    

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data');
    }
};
