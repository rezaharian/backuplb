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
        Schema::create('vmacunits', function (Blueprint $table) {
            $table->id();
            $table->string('line');
            $table->string('V_EXT');
            $table->string('V_INC');
            $table->string('V_PRT');
            $table->string('V_PRT_SBT');
            $table->string('V_PRN');
            $table->string('V_CAP');
            $table->string('NL');
            $table->string('KETER');
            $table->string('JML_SHIFT');
            $table->string('BERLAKU');
            $table->string('BEBAN_A');
            $table->string('AREA');
            $table->string('isDeleted');
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
        Schema::dropIfExists('vmacunits');
    }
};
