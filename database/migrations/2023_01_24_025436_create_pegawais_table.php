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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->integer('noreg_awal')->nullable();
            $table->integer('no_reg')->nullable();
            $table->integer('tgl_noreg')->nullable();
            $table->integer('no_peg')->nullable();
            $table->integer('no_payroll')->nullable();
            $table->string('nama')->nullable();
            $table->string('nama_asli')->nullable();
            $table->string('temp_lahir')->nullable();
            $table->string('tgl_lahir')->nullable();
            $table->string('sex')->nullable();
            $table->string('agama')->nullable();
            $table->string('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('departemen')->nullable();
            $table->string('bagian')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('golongan')->nullable();
            $table->string('unit')->nullable();
            $table->string('line')->nullable();
            $table->integer('group_group')->nullable();
            $table->integer('level')->nullable();
            $table->integer('ec_cod')->nullable();
            $table->integer('tgl_gkcod')->nullable();
            $table->integer('daerahasal')->nullable();
            $table->integer('suku_bangs')->nullable();
            $table->integer('gol_dar')->nullable();
            $table->integer('statpeng')->nullable();
            $table->integer('jns_peg')->nullable();
            $table->integer('sts_nikah')->nullable();
            $table->integer('jml_anak')->nullable();
            $table->integer('pend')->nullable();
            $table->integer('telepon')->nullable();
            $table->integer('seksi')->nullable();
            $table->integer('kode_gol')->nullable();
            $table->integer('kode_jam')->nullable();
            $table->integer('no_astek')->nullable();
            $table->integer('ms-krjbln')->nullable();
            $table->integer('tgl_masuk')->nullable();
            $table->integer('perpanjang')->nullable();
            $table->integer('berakhir')->nullable();
            $table->integer('tgl_keluar')->nullable();
            $table->integer('ayah')->nullable();
            $table->integer('ibu')->nullable();
            $table->integer('suami_istri')->nullable();
            $table->integer('no_ktp')->nullable();
            $table->integer('tglberlaku')->nullable();
            $table->integer('npwp')->nullable();
            $table->integer('no_kk')->nullable();
            $table->integer('psycotect')->nullable();
            $table->integer('tahun_psy')->nullable();
            $table->integer('temp_psy')->nullable();
            $table->integer('psy_untuk')->nullable();
            $table->integer('ket_lain')->nullable();
            $table->integer('transport')->nullable();
            $table->integer('ngaji')->nullable();
            $table->integer('kd_fas')->nullable();
            $table->integer('faskes')->nullable();
            $table->integer('rek_bank')->nullable();
            $table->integer('bpjs_tk')->nullable();
            $table->integer('bpjs_kes0')->nullable();
            $table->integer('bpjs_kes1')->nullable();
            $table->integer('bpjs_kes2')->nullable();
            $table->integer('bpjs_kes3')->nullable();
            $table->integer('bpjs_kes4')->nullable();
            $table->integer('bpjs_kes5')->nullable();
            $table->integer('updated')->nullable();
            $table->integer('foto')->nullable();
            $table->integer('email')->nullable();
            $table->integer('fileslip')->nullable();
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
        Schema::dropIfExists('pegawais');
    }
};
