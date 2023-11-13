<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->default('admin@gmail.com');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default(Hash::make('admin123'));
            $table->string('level')->default('Manager dong');
            // tambahan untuk role id ditable role
            $table->unsignedBigInteger('role_id')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });


        DB::table('users')->insert(
            ['name' => 'admin'], 
        );
        DB::table('users')->insert(
            ['name' => 'hr',
            'email' => 'hr@gmail.com',
            'password'=> Hash::make('hr123456'),
            'level' => 'HRD',
            'role_id' => 3], 
        );
        DB::table('users')->insert(
            ['name' => 'tr',
            'email' => 'tr@gmail.com',
            'password'=> Hash::make('tr123456'),
            'level' => 'Trainer',
            'role_id' => 5], 
        );
        DB::table('users')->insert(
            ['name' => 'qc',
            'email' => 'qc@gmail.com',
            'password'=> Hash::make('qc123456'),
            'level' => 'QIUSI',
            'role_id' => 6], 
        );
        DB::table('users')->insert(
            ['name' => 'op',
            'email' => 'op@gmail.com',
            'password'=> Hash::make('op123456'),
            'level' => 'Opherators',
            'role_id' => 7], 
        );
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
