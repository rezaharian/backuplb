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
        Schema::create('ddata_masters', function (Blueprint $table) {
            $table->id();
            $table->string('shift');
            $table->timestamps();
        });

        DB::table('ddata_masters')->insert(
            ['shift' => 1 ],
          
        );
        
        DB::table('ddata_masters')->insert(
            ['shift' => 2 ],
          
        );
        
        DB::table('ddata_masters')->insert(
            ['shift' => 3 ],
          
        );
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ddata_masters');
    }
};
