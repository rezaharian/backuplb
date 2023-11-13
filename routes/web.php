<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\apiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AutoFingerController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\BosController;
use App\Http\Controllers\DashboardSuperAdminController;
use App\Http\Controllers\DashboardSuperAdminCapController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\DataPegawaiController;
use App\Http\Controllers\FingerprintController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\hrPrimeController;
use App\Http\Controllers\hrReportIcbController;
use App\Http\Controllers\hrReportIpaMdtController;
use App\Http\Controllers\hrReportKerajinanController;
use App\Http\Controllers\hrReportLkkController;
use App\Http\Controllers\hrReportTlAkController;
use App\Http\Controllers\HrTrainingController;
use App\Http\Controllers\JalurController;
use App\Http\Controllers\kompetensiController;
use App\Http\Controllers\KtigaController;
use App\Http\Controllers\KtigaUserController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\OnOffController;
use App\Http\Controllers\OpController;
use App\Http\Controllers\OverTimeController;
use App\Http\Controllers\PemateriController;
use App\Http\Controllers\PenilaianKerjaController;
use App\Http\Controllers\PenilaianKerjaControllerTrainer;
use App\Http\Controllers\primeController;
use App\Http\Controllers\ProblemMesinController;
use App\Http\Controllers\QcController;
use App\Http\Controllers\ReportAbsenController;
use App\Http\Controllers\ReportCutiController;
use App\Http\Controllers\srtCutiController;
use App\Http\Controllers\TargetTrainingController;
use App\Http\Controllers\TdkAbsenController;
use App\Http\Controllers\TglLiburController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\trainingController;
use App\Http\Controllers\UmumAbsenController;
use App\Http\Controllers\UmumController;
use App\Http\Controllers\UmumOverTimeController;
use App\Http\Controllers\umumReportCutiController;
use App\Http\Controllers\umumReportIcbController;
use App\Http\Controllers\umumReportIpaMdtController;
use App\Http\Controllers\umumReportKtmhController;
use App\Http\Controllers\umumReportLkkController;
use App\Http\Controllers\umumReportPresensiController;
use App\Http\Controllers\umumReportTlAkController;
use App\Http\Controllers\umumSrtCutiController;
use App\Models\pegawai;
use App\Models\TargetTraining;
use App\Models\train_d;
use App\Models\User;
use GuzzleHttp\Psr7\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//  jika user belum login
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'dologin']);
    // untuk autonarikfinger
    Route::post('/run-script', [AutoFingerController::class, 'exportData'])->name('run.script');
});

// untuk superadmin, bos dan pegawai
Route::group(['middleware' => ['auth', 'checkrole:1,2,3,4,5,6,7,8']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/redirect', [RedirectController::class, 'cek']);

    //menambahkan field master shift
    route::post('/store-input-fields-master', [DataMasterController::class, 'store']);
});

// untuk superadmin
Route::group(['middleware' => ['auth', 'checkrole:1']], function () {
    // Route::get('/superadmin', [SuperadminController::class, 'index']);
    Route::get('/superadmin', [DashboardSuperAdminController::class, 'index']);

    //user
    route::get('/superadmin/dashboard/user', [DashboardSuperAdminController::class, 'indexUser'])->name('superadmin.user.index');
    route::post('/superadmin/dashboard/user/store', [DashboardSuperAdminController::class, 'storeUser']);
    route::delete('/superadmin/dashboard/user/destroy/{id}', [DashboardSuperAdminController::class, 'destroyUser'])->name('superadmin.destroy.user');
    route::get('/superadmin/dashboard/user/edit/{id}', [DashboardSuperAdminController::class, 'editUser'])->name('superadmin.edit.user');
    route::post('/superadmin/dashboard/user/update/{id}', [DashboardSuperAdminController::class, 'updateUser'])->name('superadmin.update.user');
    route::get('/superadmin/dashboard/user/delete_akses/{id}', [DashboardSuperAdminController::class, 'delete_akses'])->name('superadmin.delete_akses.user');

    //profile
    route::get('/superadmin/dashboard/profile', [DashboardSuperAdminController::class, 'profileAdmin'])->name('superadmin.dashboard.profile');

    //LogBook
    route::get('/superadmin/dashboard/loogbok/{jalur_id}/data', [DashboardSuperAdminController::class, 'indexData'])->name('superadmin.index.data');
    route::get('/superadmin/dashboard/logbook/{jalur_id}/pencarian', [DashboardSuperAdminController::class, 'indexCariData'])->name('superadmin.index.cari.data');
    route::post('/superadmin/dashboard/loogbok/{jalur_id}/update/{data}', [DashboardSuperAdminController::class, 'update'])->name('superadmin.update.data');
    route::get('/superadmin/dashboard/loogbok/{jalur_id}/reset/{data}', [DashboardSuperAdminController::class, 'reset'])->name('superadmin.reset.data');
    route::get('/superadmin/dashboard/loogbok/{jalur_id}/batalresetpcs/{data}', [DashboardSuperAdminController::class, 'batalresetpcs'])->name('superadmin.batalresetpcs.data');
    route::get('/superadmin/dashboard/loogbok/{jalur_id}/batalresetpcsfresh/{data}', [DashboardSuperAdminController::class, 'batalresetpcsrefresh'])->name('superadmin.batalresetpcsrefresh.data');
    route::get('/superadmin/dashboard/loogbok/{jalur_id}/resetwaktu/{data}', [DashboardSuperAdminController::class, 'resetwaktu'])->name('superadmin.resetwaktu.data');
    route::get('/superadmin/dashboard/loogbok/{jalur_id}/batalresettime/{data}', [DashboardSuperAdminController::class, 'batalresettime'])->name('superadmin.batalresettime.data');
    route::post('/superadmin/dashboard/loogbok/insertfielddata/{jalur_id}', [DashboardSuperAdminController::class, 'insertfielddata'])->name('superadmin.insertfielddata.data');
    route::get('/superadmin/dashboard/loogbok/delete/{jalur_id}', [DashboardSuperAdminController::class, 'deleteTmpData'])->name('superadmin.delete.data');
    Route::get('/userautocomplete', [DashboardSuperAdminController::class, 'userautocomplete'])->name('userautocomplete');

    //LogBook Capp
    route::get('/superadmin/dashboard/loogbokcap/{jalur_id}/data', [DashboardSuperAdminCapController::class, 'indexData'])->name('superadmin.index.datacap');
    route::get('/superadmin/dashboard/logbookcap/{jalur_id}/pencarian', [DashboardSuperAdminCapController::class, 'indexCariData'])->name('superadmin.index.cari.datacap');
    route::post('/superadmin/dashboard/loogbokcap/{jalur_id}/update/{data}', [DashboardSuperAdminCapController::class, 'update'])->name('superadmin.update.datacap');
    route::get('/superadmin/dashboard/loogbokcap/{jalur_id}/reset/{data}', [DashboardSuperAdminCapController::class, 'reset'])->name('superadmin.reset.datacap');
    route::get('/superadmin/dashboard/loogbokcap/{jalur_id}/batalresetpcs/{data}', [DashboardSuperAdminCapController::class, 'batalresetpcs'])->name('superadmin.batalresetpcs.datacap');
    route::get('/superadmin/dashboard/loogbokcap/{jalur_id}/batalresetpcsfresh/{data}', [DashboardSuperAdminCapController::class, 'batalresetpcsrefresh'])->name('superadmin.batalresetpcsrefresh.datacap');
    route::get('/superadmin/dashboard/loogbokcap/{jalur_id}/resetwaktu/{data}', [DashboardSuperAdminCapController::class, 'resetwaktu'])->name('superadmin.resetwaktu.datacap');
    route::get('/superadmin/dashboard/loogbokcap/{jalur_id}/batalresettime/{data}', [DashboardSuperAdminCapController::class, 'batalresettime'])->name('superadmin.batalresettime.datacap');
    route::post('/superadmin/dashboard/loogbokcap/insertfielddata/{jalur_id}', [DashboardSuperAdminCapController::class, 'insertfielddata'])->name('superadmin.insertfielddata.datacap');
    route::get('/superadmin/dashboard/loogbokcap/delete/{jalur_id}', [DashboardSuperAdminCapController::class, 'deleteTmpData'])->name('superadmin.delete.datacap');

    //jalur
    route::get('/superadmin/dashboard/loogbok/line', [DashboardSuperAdminController::class, 'lineloogbok'])->name('superadmin.line.data');
    route::post('/superadmin/dashboard/jalur/store', [DashboardSuperAdminController::class, 'storeJalur'])->name('superadmin.line.store');
    //jalur Capping
    route::get('/superadmin/dashboard/loogbok/linecap', [DashboardSuperAdminCapController::class, 'lineloogbokcap'])->name('superadmin.linecap.data');
    route::post('/superadmin/dashboard/jalurcap/store', [DashboardSuperAdminCapController::class, 'storeJalurcap'])->name('superadmin.linecap.store');

    // Problem Mesin
    Route::get('/superadmin/dashboard/problemmsn', [ProblemMesinController::class, 'index'])->name('adm.problemmsn.index');
    Route::get('/superadmin/dashboard/problemmsn/list', [ProblemMesinController::class, 'list'])->name('adm.problemmsn.list');
    Route::get('/superadmin/dashboard/problemmsn/view/{id}', [ProblemMesinController::class, 'view'])->name('adm.problemmsn.view');
    Route::get('/superadmin/dashboard/problemmsn/view_d/{id}', [ProblemMesinController::class, 'view_d'])->name('adm.problemmsn.view_d');
    Route::get('/superadmin/dashboard/problemmsn/create', [ProblemMesinController::class, 'create'])->name('adm.problemmsn.create');
    Route::post('/superadmin/dashboard/problemmsn/store', [ProblemMesinController::class, 'store'])->name('adm.problemmsn.store');
    Route::get('/superadmin/dashboard/problemmsn/edit/{id}', [ProblemMesinController::class, 'edit'])->name('adm.problemmsn.edit');
    Route::post('/superadmin/dashboard/problemmsn/update/{id}', [ProblemMesinController::class, 'update'])->name('adm.problemmsn.update');
    Route::get('/superadmin/dashboard/problemmsn/delete/{id}', [ProblemMesinController::class, 'delete'])->name('adm.problemmsn.delete');
    Route::delete('/superadmin/dashboard/problemmsn/delete_d/{id}', [ProblemMesinController::class, 'delete_d'])->name('adm.problemmsn.delete_d');
    Route::get('/superadmin/dashboard/problemmsn/print/{id}', [ProblemMesinController::class, 'print'])->name('adm.problemmsn.print');
    Route::get('/superadmin/dashboard/problemmsn/print_d/{id}', [ProblemMesinController::class, 'print_d'])->name('adm.problemmsn.print_d');
});

// untuk bos
Route::group(['middleware' => ['auth', 'checkrole:2']], function () {
    Route::get('/bos', [BosController::class, 'index']);
});

// untuk pegawai
Route::group(['middleware' => ['auth', 'checkrole:4']], function () {
    Route::get('/pegawai', [PegawaiController::class, 'index']);
});

//HR
Route::group(['middleware' => ['auth', 'checkrole:3']], function () {
    // FP coba
    Route::get('/hr/fingerprint', [FingerprintController::class, 'index']);
    Route::get('/hr/fingerprint', [FingerprintController::class, 'filter'])->name('fingerprint.filter');
    Route::post('/hr/fingerprint/export', [FingerprintController::class, 'export'])->name('fingerprint.export');

    //profile
    Route::get('/hr/dashboard/profile/index', [HrController::class, 'index_profile'])->name('hr.profile.index_profile');
    Route::get('/hr/dashboard/profile/edit', [HrController::class, 'edit_profile'])->name('hr.profile.edit_profile');
    Route::post('/hr/dashboard/profile/update/{id}', [HrController::class, 'update_profile'])->name('hr.profile.update_profile');

    // bagian
    Route::get('/hr', [HrController::class, 'index']);
    Route::get('/coba', [HrController::class, 'coba']);
    Route::get('/chart_peg_bag', [HrController::class, 'chart_peg_bag']);
    Route::get('/hr/dashboard/training/bagian/index', [HrTrainingController::class, 'bagianIndex'])->name('hr.bagian.index');
    Route::post('/hr/dashboard/training/bagian/store', [HrTrainingController::class, 'storebag'])->name('hr.bagian.store');
    Route::delete('/hr/dashboard/training/bagian/delete{id}', [HrTrainingController::class, 'delete'])->name('hr.bagian.delete');
    Route::get('/hr/dashboard/training/bagian/edit/{id}', [HrTrainingController::class, 'edit'])->name('hr.bagian.edit');
    Route::post('/hr/dashboard/training/bagian/update/{id}', [HrTrainingController::class, 'update'])->name('hr.bagian.update');
    // Kompetensi
    Route::get('/hr/dashboard/training/kompetensi/list', [kompetensiController::class, 'list'])->name('hr.kompetensi.list');
    Route::get('/hr/dashboard/training/kompetensi/view/{id}', [kompetensiController::class, 'view'])->name('hr.kompetensi.view');
    Route::get('/hr/dashboard/training/kompetensi/delete/{id}', [kompetensiController::class, 'delete'])->name('hr.kompetensi.delete');
    Route::post('/hr/dashboard/training/kompetensi/update/{id}', [kompetensiController::class, 'update'])->name('hr.kompetensi.update');
    Route::get('/hr/dashboard/training/kompetensi/edit/{id}', [kompetensiController::class, 'edit'])->name('hr.kompetensi.edit');
    Route::get('/hr/dashboard/training/kompetensi/create', [kompetensiController::class, 'create'])->name('hr.kompetensi.create');
    Route::post('/hr/dashboard/training/kompetensi/store', [kompetensiController::class, 'store'])->name('hr.kompetensi.store');
    Route::post('/hr/dashboard/training/kompetensi/delete_d/{id}', [kompetensiController::class, 'delete_d'])->name('hr.kompetensi.delete_d');
    // training
    Route::get('/hr/dashboard/training/tr/list', [trainingController::class, 'list'])->name('hr.training.list');
    Route::get('/hr/dashboard/training/tr/view/{id}', [trainingController::class, 'view'])->name('hr.training.view');
    Route::get('/hr/dashboard/training/tr/delete/{id}', [trainingController::class, 'delete'])->name('hr.training.delete');
    Route::get('/autocompleted', [trainingController::class, 'autocomplete'])->name('hr.training.autocomplete');
    Route::get('/autocompleted_pegawai', [trainingController::class, 'autocompleted_pegawai'])->name('hr.training.autocompleted_pegawai');
    Route::get('/hr/dashboard/training/tr/create', [trainingController::class, 'create'])->name('hr.training.create');
    Route::get('/hr/dashboard/training/tr/cb', [trainingController::class, 'cb'])->name('hr.training.cb');
    Route::post('/hr/dashboard/training/tr/store', [trainingController::class, 'store'])->name('hr.training.store');
    Route::get('/hr/dashboard/training/tr/edit/{id}', [trainingController::class, 'edit'])->name('hr.training.edit');
    Route::post('/hr/dashboard/training/tr/update/{id}', [trainingController::class, 'update'])->name('hr.training.update');
    Route::get('/hr/dashboard/training/tr/print/{id}', [trainingController::class, 'print'])->name('hr.training.print');
    Route::get('/hr/dashboard/training/tr/delete_d/{id}', [trainingController::class, 'delete_d'])->name('hr.training.delete_d');
    Route::get('/hr/dashboard/training/tr/train_trash/{id}', [trainingController::class, 'train_trash'])->name('hr.training.train_trash');
    Route::get('/hr/dashboard/training/tr/train_trash/restore_trash/{id}', [trainingController::class, 'restore_trash'])->name('hr.training.train_trash.restore_trash');
    Route::get('/hr/dashboard/training/tr/train_trash/restore_all/{id}', [trainingController::class, 'restore_all'])->name('hr.training.train_trash.restore_all');
    //Pegawai
    Route::get('/hr/dashboard/pegawai/index', [DataPegawaiController::class, 'index'])->name('datapegawai.index');
    Route::get('/hr/dashboard/pegawai/create', [DataPegawaiController::class, 'create'])->name('datapegawai.create');
    Route::get('/hr/dashboard/pegawai/create_satpamkebersihan', [DataPegawaiController::class, 'create_satpamkebersihan'])->name('datapegawai.create_satpamkebersihan');
    Route::post('/hr/dashboard/pegawai/store', [DataPegawaiController::class, 'store'])->name('datapegawai.store');
    Route::get('/hr/dashboard/pegawai/detail/{id}', [DataPegawaiController::class, 'detail'])->name('datapegawai.detail');
    Route::get('/hr/dashboard/pegawai/delete/{id}', [DataPegawaiController::class, 'delete'])->name('datapegawai.delete');
    Route::get('/hr/dashboard/pegawai/edit/{id}', [DataPegawaiController::class, 'edit'])->name('datapegawai.edit');
    Route::get('/hr/dashboard/pegawai/print/{id}', [DataPegawaiController::class, 'print'])->name('datapegawai.print');
    Route::post('/hr/dashboard/pegawai/update/{id}', [DataPegawaiController::class, 'update'])->name('datapegawai.update');
    //delete_detail_pegawai
    Route::get('/hr/dashboard/pegawai/delete_d_keluarga/{id}', [DataPegawaiController::class, 'delete_d_keluarga'])->name('datapegawai.delete_d_keluarga');
    Route::get('/hr/dashboard/pegawai/delete_d_pendidikan/{id}', [DataPegawaiController::class, 'delete_d_pendidikan'])->name('datapegawai.delete_d_pendidikan');
    Route::get('/hr/dashboard/pegawai/delete_d_pelatihan/{id}', [DataPegawaiController::class, 'delete_d_pelatihan'])->name('datapegawai.delete_d_pelatihan');
    Route::get('/hr/dashboard/pegawai/delete_d_exp/{id}', [DataPegawaiController::class, 'delete_d_exp'])->name('datapegawai.delete_d_exp');
    Route::get('/hr/dashboard/pegawai/delete_d_kontrak/{id}', [DataPegawaiController::class, 'delete_d_kontrak'])->name('datapegawai.delete_d_kontrak');

    //pemateri
    // Route::get('/hr/dashboard/training/pemateri/index', [PemateriController::class, 'index'])->name('pemateri.index');
    // Route::delete('/hr/dashboard/training/pemateri/delete{id}', [PemateriController::class, 'delete'])->name('pemateri.delete');
    // Route::get('/hr/dashboard/training/pemateri/edit/{id}', [PemateriController::class, 'edit'])->name('pemateri.edit');
    // Route::post('/hr/dashboard/training/pemateri/update/{id}', [PemateriController::class, 'update'])->name('pemateri.update');
    // Route::post('/hr/dashboard/training/pemateri/store', [PemateriController::class, 'store'])->name('pemateri.store');
    //laporan
    Route::get('/hr/dashboard/training/rek_tra', [trainingController::class, 'rek_tra'])->name('hr.training.rek_tra');
    Route::get('/hr/dashboard/training/tra_kar', [trainingController::class, 'tra_kar'])->name('hr.training.tra_kar');
    Route::get('/hr/dashboard/training/tra_kar_detail/{id}/{dtv1}/{dtv2}/{jenis}', [trainingController::class, 'tra_kar_detail'])->name('hr.training.tra_kar_detail');
    Route::get('/hr/dashboard/training/tra_kar_print_detail/{id}/{dtv1}/{dtv2}/{jenis}', [trainingController::class, 'tra_kar_print_detail'])->name('hr.training.tra_kar_print_detail');
    Route::get('/hr/dashboard/training/rek_tra_print', [trainingController::class, 'rek_tra_print'])->name('hr.training.rek_tra_print');
    Route::get('/hr/dashboard/training/tra_kar_print', [trainingController::class, 'tra_kar_print'])->name('hr.training.tra_kar_print');
    //Materi
    Route::get('/hr/dashboard/training/materi/list', [MateriController::class, 'list'])->name('hr.training.materi.list');
    Route::post('/hr/dashboard/training/materi/create', [MateriController::class, 'create'])->name('hr.training.materi.create');
    Route::get('/hr/dashboard/training/materi/delete/{id}', [MateriController::class, 'delete'])->name('hr.training.materi.delete');
    Route::get('/hr/dashboard/training/materi/edit/{id}', [MateriController::class, 'edit'])->name('hr.training.materi.edit');
    Route::get('/hr/dashboard/training/materi/view/{id}', [MateriController::class, 'view'])->name('hr.training.materi.view');
    Route::post('/hr/dashboard/training/materi/update/{id}', [MateriController::class, 'update'])->name('hr.training.materi.update');
    //laporan Materi
    Route::get('/hr/dashboard/training/materi/laporan/list', [MateriController::class, 'laporan_list'])->name('hr.training.materi.laporan.list');
    Route::post('id/find_bag', [MateriController::class, 'find_bag'])->name('hr.training.materi.laporan.find_bag');
    Route::get('/hr/dashboard/training/materi/laporan/list_print/', [MateriController::class, 'laporan_list_print'])->name('hr.training.materi.laporan.list_print');
    //Target Training
    Route::get('/hr/dashboard/training/target/list', [TargetTrainingController::class, 'list'])->name('hr.training.target.list');
    Route::post('/hr/dashboard/training/target/create', [TargetTrainingController::class, 'create'])->name('hr.training.target.create');
    Route::get('/hr/dashboard/training/target/delete/{id}', [TargetTrainingController::class, 'delete'])->name('hr.training.target.delete');
    Route::get('/hr/dashboard/training/target/edit/{id}', [TargetTrainingController::class, 'edit'])->name('hr.training.target.edit');
    Route::post('/hr/dashboard/training/target/update/{id}', [TargetTrainingController::class, 'update'])->name('hr.training.target.update');
    //laporan target
    Route::get('/hr/dashboard/training/target/laporan/list', [TargetTrainingController::class, 'laporan_list'])->name('hr.training.target.laporan.list');
    Route::get('/hr/dashboard/training/target/laporan/print/', [TargetTrainingController::class, 'laporan_list_print'])->name('hr.training.target.laporan.print');
    //laporan Kontrak Pegawai
    Route::get('/hr/dashboard/pegawai/kontrak/laporan/list', [DataPegawaiController::class, 'laporan_list'])->name('datapegawai.laporan_list');
    Route::get('/hr/dashboard/pegawai/kontrak/laporan/print/{bln}/{thn}', [DataPegawaiController::class, 'print_laporan_list'])->name('datapegawai.print_laporan_list');

    //Absen
    Route::get('/hr/dashboard/absen/list', [AbsenController::class, 'list'])->name('hr.absen.list');
    Route::get('/hr/dashboard/absen/create/{bln}/{thn}/', [AbsenController::class, 'create'])->name('hr.absen.create');
    Route::get('/find_pegawai/{id}', [AbsenController::class, 'find_pegawai'])->name('hr.absen.find_pegawai');
    Route::get('/find_absen_h/{id}', [AbsenController::class, 'find_absen_h'])->name('hr.absen.find_absen_h');
    Route::post('/hr/dashboard/absen/store/{bln}/{thn}/', [AbsenController::class, 'store'])->name('hr.absen.store');
    Route::get('/hr/dashboard/absen/detail/{bln}/{thn}/{id}/', [AbsenController::class, 'detail'])->name('hr.absen.detail');
    Route::get('/hr/dashboard/absen/edit/{id}/', [AbsenController::class, 'edit'])->name('hr.absen.edit');
    Route::get('/hr/dashboard/absen/print/{bln}/{thn}/{id}', [AbsenController::class, 'print'])->name('hr.absen.print');
    Route::post('/hr/dashboard/absen/update/{id}/', [AbsenController::class, 'update'])->name('hr.absen.update');
    Route::get('/hr/dashboard/absen/delete_d/{id}/', [AbsenController::class, 'delete_d'])->name('hr.absen.delete_d');
    Route::get('/hr/dashboard/absen/delete/{id}/', [AbsenController::class, 'delete'])->name('hr.absen.delete');

    //Over Time
    Route::get('hr/dashboard/overt/list', [OverTimeController::class, 'list'])->name('hr.overt.list');
    Route::get('hr/dashboard/overt/create', [OverTimeController::class, 'create'])->name('hr.overt.create');
    Route::post('hr/dashboard/overt/store', [OverTimeController::class, 'store'])->name('hr.overt.store');
    Route::get('hr/dashboard/overt/detail/{id}', [OverTimeController::class, 'detail'])->name('hr.overt.detail');
    Route::get('hr/dashboard/overt/edit/{id}', [OverTimeController::class, 'edit'])->name('hr.overt.edit');
    Route::get('hr/dashboard/overt/delete/{id}', [OverTimeController::class, 'delete'])->name('hr.overt.delete');
    Route::post('hr/dashboard/overt/update/{id}', [OverTimeController::class, 'update'])->name('hr.overt.update');
    Route::get('hr/dashboard/overt/delete_d/{id}', [OverTimeController::class, 'delete_d'])->name('hr.overt.delete_d');
    Route::get('hr/dashboard/overt/print/{id}', [OverTimeController::class, 'print'])->name('hr.overt.print');

    //Tgl Libur
    Route::get('/hr/dashboard/tgllibur/list', [TglLiburController::class, 'list'])->name('hr.tgllibur.list');
    Route::post('/hr/dashboard/tgllibur/create', [TglLiburController::class, 'create'])->name('hr.tgllibur.create');
    Route::get('/hr/dashboard/tgllibur/delete/{id}', [TglLiburController::class, 'delete'])->name('hr.tgllibur.delete');
    Route::get('/hr/dashboard/tgllibur/edit/{id}', [TglLiburController::class, 'edit'])->name('hr.tgllibur.edit');
    Route::post('/hr/dashboard/tgllibur/update/{id}', [TglLiburController::class, 'update'])->name('hr.tgllibur.update');

    //Tdk ABsen
    Route::get('/hr/dashboard/tdkabsen/list', [TdkAbsenController::class, 'list'])->name('hr.tdkabsen.list');
    Route::post('/hr/dashboard/tdkabsen/create', [TdkAbsenController::class, 'create'])->name('hr.tdkabsen.create');
    Route::get('/hr/dashboard/tdkabsen/delete/{id}', [TdkAbsenController::class, 'delete'])->name('hr.tdkabsen.delete');
    Route::get('/hr/dashboard/tdkabsen/edit/{id}', [TdkAbsenController::class, 'edit'])->name('hr.tdkabsen.edit');
    Route::post('/hr/dashboard/tdkabsen/update/{id}', [TdkAbsenController::class, 'update'])->name('hr.tdkabsen.update');

    Route::get('/autocompleted_tdkabsen', [TdkAbsenController::class, 'autocompleted_tdkabsen'])->name('hr.tdkabsen.autocompleted_tdkabsen');

    //ktiga
    Route::get('/hr/dashboard/ktiga/list', [KtigaController::class, 'list'])->name('hr.ktiga.list');
    Route::get('/hr/dashboard/ktiga/create', [KtigaController::class, 'create'])->name('hr.ktiga.create');
    Route::post('/hr/dashboard/ktiga/store', [KtigaController::class, 'store'])->name('hr.ktiga.store');
    Route::get('/hr/dashboard/ktiga/delete/{id}', [KtigaController::class, 'delete'])->name('hr.ktiga.delete');
    Route::get('/hr/dashboard/ktiga/edit/{id}', [KtigaController::class, 'edit'])->name('hr.ktiga.edit');
    Route::get('/hr/dashboard/ktiga/view/{id}', [KtigaController::class, 'view'])->name('hr.ktiga.view');
    Route::post('/hr/dashboard/ktiga/update/{id}', [KtigaController::class, 'update'])->name('hr.ktiga.update');
    Route::get('/hr/dashboard/ktiga/print/{id}', [KtigaController::class, 'print'])->name('hr.ktiga.print');

    //report ktiga
    Route::get('/hr/dashboard/ktiga/reportktiga', [KtigaController::class, 'reportktiga'])->name('hr.ktiga.reportktiga');
    Route::get('/hr/dashboard/ktiga/reportktigaprint', [KtigaController::class, 'reportktigaprint'])->name('hr.ktiga.reportktigaprint');

    //report Absen
    Route::get('/hr/dashboard/reportabsen/index', [ReportAbsenController::class, 'index'])->name('hr.reportabsen.index');
    Route::get('/hr/dashboard/reportabsen/list', [ReportAbsenController::class, 'list'])->name('hr.reportabsen.list');
    Route::get('/hr/dashboard/reportabsen/list_print', [ReportAbsenController::class, 'list_print'])->name('hr.reportabsen.list_print');
    Route::get('/hr/dashboard/reportabsen/uraianlembur', [ReportAbsenController::class, 'uraianlembur'])->name('hr.reportabsen.uraianlembur');
    Route::get('/hr/dashboard/reportabsen/uraianlembur_print', [ReportAbsenController::class, 'uraianlembur_print'])->name('hr.reportabsen.uraianlembur_print');
    Route::get('/hr/dashboard/reportabsen/rekapgaji', [ReportAbsenController::class, 'rekapgaji'])->name('hr.reportabsen.rekapgaji');
    Route::get('/hr/dashboard/reportabsen/rekapgaji_print', [ReportAbsenController::class, 'rekapgaji_print'])->name('hr.reportabsen.rekapgaji_print');
    Route::get('/hr/dashboard/reportabsen/rekapgaji_excel', [ReportAbsenController::class, 'rekapgaji_excel'])->name('hr.reportabsen.rekapgaji_excel');

    // report cuti
    Route::get('/hr/dashboard/reportcuti/index', [ReportCutiController::class, 'index'])->name('hr.reportcuti.index');
    Route::get('/hr/dashboard/reportcuti/list', [ReportCutiController::class, 'list'])->name('hr.reportcuti.list');

    // ON OFF
    Route::get('/hr/dashboard/onoff/index', [OnOffController::class, 'index'])->name('hr.onoff.index');
    Route::post('/hr/dashboard/onoff/create', [OnOffController::class, 'create'])->name('hr.onoff.create');
    Route::get('/hr/dashboard/onoff/edit/{id}', [OnOffController::class, 'edit'])->name('hr.onoff.edit');
    Route::post('/hr/dashboard/onoff/update/{id}', [OnOffController::class, 'update'])->name('hr.onoff.update');
    Route::get('/hr/dashboard/onoff/delete/{id}', [OnOffController::class, 'delete'])->name('hr.onoff.delete');

    //PENILAIAN KERJA
    Route::get('/hr/dashboard/penilaiankerja/index', [PenilaianKerjaController::class, 'index'])->name('hr.penilaiankerja.index');
    Route::get('/hr/dashboard/penilaiankerja/view/{id}', [PenilaianKerjaController::class, 'view'])->name('hr.penilaiankerja.view');
    Route::get('/hr/dashboard/penilaiankerja/create', [PenilaianKerjaController::class, 'create'])->name('hr.penilaiankerja.create');
    Route::post('/hr/dashboard/penilaiankerja/store', [PenilaianKerjaController::class, 'store'])->name('hr.penilaiankerja.store');
    Route::get('/hr/dashboard/penilaiankerja/edit/{id}', [PenilaianKerjaController::class, 'edit'])->name('hr.penilaiankerja.edit');
    Route::post('/hr/dashboard/penilaiankerja/update/{id}', [PenilaianKerjaController::class, 'update'])->name('hr.penilaiankerja.update');
    Route::get('/hr/dashboard/penilaiankerja/delete/{id}', [PenilaianKerjaController::class, 'delete'])->name('hr.penilaiankerja.delete');
    Route::get('/hr/dashboard/penilaiankerja/print/{id}', [PenilaianKerjaController::class, 'print'])->name('hr.penilaiankerja.print');

    Route::get('/hr/dashboard/penilaiankerjareport/laporan', [PenilaianKerjaController::class, 'laporan'])->name('hr.penilaiankerja.laporan');
    Route::get('/hr/dashboard/penilaiankerjareport/listLaporan', [PenilaianKerjaController::class, 'listLaporan'])->name('hr.penilaiankerja.listlaporan');

    Route::get('/kelengkapandatapegawai/{no_payroll}', [PenilaianKerjaController::class, 'kelengkapanpegawai']);
    Route::get('/kelengkapandataperbagianhr/{bagian}', [PenilaianKerjaController::class, 'kelengkapandataperbagianhr']);
    // Route::get('/reqbagian/{bagian}', [PenilaianKerjaController::class, 'reqbagian']);

    // REPORT ICB
    Route::get('/hr/dashboard/reporticb/index', [hrReportIcbController::class, 'index'])->name('hr.reporticb.index');
    Route::get('/hr/dashboard/reporticb/list', [hrReportIcbController::class, 'list'])->name('hr.reporticb.list');
    Route::get('/hr/dashboard/reporticb/print', [hrReportIcbController::class, 'print'])->name('hr.reporticb.print');

    // REPORT IPA MDT
    Route::get('/hr/dashboard/reportipamdt/index', [hrReportIpaMdtController::class, 'index'])->name('hr.reportipamdt.index');
    Route::get('/hr/dashboard/reportipamdt/list', [hrReportIpaMdtController::class, 'list'])->name('hr.reportipamdt.list');
    Route::get('/hr/dashboard/reportipamdt/print', [hrReportIpaMdtController::class, 'print'])->name('hr.reportipamdt.print');

    // REPORT IPA MDT KTMH
    Route::get('/hr/dashboard/reporttlak/index', [hrReportTlAkController::class, 'index'])->name('hr.reporttlak.index');
    Route::get('/hr/dashboard/reporttlak/list', [hrReportTlAkController::class, 'list'])->name('hr.reporttlak.list');
    Route::get('/hr/dashboard/reporttlak/print', [hrReportTlAkController::class, 'print'])->name('hr.reporttlak.print');

    // REPORT LKK
    Route::get('/hr/dashboard/reportlkk/index', [hrReportLkkController::class, 'index'])->name('hr.reportlkk.index');
    Route::get('/hr/dashboard/reportlkk/list', [hrReportLkkController::class, 'list'])->name('hr.reportlkk.list');
    Route::get('/hr/dashboard/reportlkk/print', [hrReportLkkController::class, 'print'])->name('hr.reportlkk.print');

    // prime
    Route::get('/hrpegawailengkap', [hrPrimeController::class, 'hrpegawailengkap']);

    // Surat Cuti
    Route::get('/hr/dashboard/srtcuti/list', [srtCutiController::class, 'list'])->name('hr.srtcuti.list');
    Route::get('/hr/dashboard/srtcuti/create', [srtCutiController::class, 'create'])->name('hr.srtcuti.create');
    Route::post('/hr/dashboard/srtcuti/store', [srtCutiController::class, 'store'])->name('hr.srtcuti.store');
    Route::get('/hr/dashboard/srtcuti/edit/{id}', [srtCutiController::class, 'edit'])->name('hr.srtcuti.edit');
    Route::get('/hr/dashboard/srtcuti/delete/{id}', [srtCutiController::class, 'delete'])->name('hr.srtcuti.delete');
    Route::get('/hr/dashboard/srtcuti/print/{id}', [srtCutiController::class, 'print'])->name('hr.srtcuti.print');
    Route::post('/hr/dashboard/srtcuti/update/{id}', [srtCutiController::class, 'update'])->name('hr.srtcuti.update');

    // REPORT LKK
    Route::get('/hr/dashboard/reportkerajinan/index', [hrReportKerajinanController::class, 'index'])->name('hr.reportkerajinan.index');
    Route::get('/hr/dashboard/reportkerajinan/list', [hrReportKerajinanController::class, 'list'])->name('hr.reportkerajinan.list');
    Route::get('/hr/dashboard/reportkerajinan/print', [hrReportKerajinanController::class, 'print'])->name('hr.reportkerajinan.print');
});

// trainer
Route::group(['middleware' => ['auth', 'checkrole:5']], function () {
    Route::get('/trainer', [TrainerController::class, 'index'])->name('index');
    Route::get('/trainer/create', [TrainerController::class, 'create'])->name('trainer.create');
    Route::get('/autocompletef', [TrainerController::class, 'autocomplete'])->name('trainer.autocomplete');
    Route::post('/trainer/store', [TrainerController::class, 'store'])->name('trainer.store');
    Route::get('/trainer/list', [TrainerController::class, 'list'])->name('trainer.list');
    Route::get('/trainer/view/{id}', [TrainerController::class, 'view'])->name('trainer.view');
    Route::get('/trainer/edit/{id}', [TrainerController::class, 'edit'])->name('trainer.edit');
    Route::post('/trainer/update/{id}', [TrainerController::class, 'update'])->name('trainerupdate');
    Route::get('/trainer/print/{id}', [TrainerController::class, 'print'])->name('trainer.print');
    Route::get('/trainer/delete/{id}', [TrainerController::class, 'delete'])->name('trainer.delete');
    Route::delete('/trainer/delete_d/{id}', [TrainerController::class, 'delete_d'])->name('training.delete_d');
    Route::get('/trainer/train_trash/{id}', [TrainerController::class, 'train_trash'])->name('training.train_trash');
    Route::get('/trainer/restore_trash/{id}', [TrainerController::class, 'restore_trash'])->name('training.restore_trash');
    Route::get('/trainer/restore_all/{id}', [TrainerController::class, 'restore_all'])->name('training.restore_all');

    //profile
    Route::get('/tr/profile_auth', [TrainerController::class, 'profile_auth'])->name('trainer.profile_auth');
    Route::get('/tr/profile_auth_edit', [TrainerController::class, 'profile_auth_edit'])->name('trainer.profile_auth_edit');
    Route::post('/tr/profile_auth_update/{id}', [TrainerController::class, 'profile_auth_update'])->name('trainer.profile_auth_update');

    //mesinx
    Route::get('/trainer/msn/list', [TrainerController::class, 'msnlist'])->name('trainer.msn.list');
    Route::get('/trainer/msn/list_d/{id}', [TrainerController::class, 'msnlist_d'])->name('trainer.msn.list_d');
    Route::get('/trainer/msn/print_d/{id}', [TrainerController::class, 'print_d'])->name('trainer.msn.print_d');
    //laporan
    Route::get('/autocomplete', [trainingController::class, 'autocomplete'])->name('hr.training.autocomplete'); //beda controller

    Route::get('/trainer/laporan/rek_tra', [TrainerController::class, 'rek_tra'])->name('trainer.training.rek_tra');
    Route::get('/trainer/laporan/tra_kar/{id}/{dtv1}/{dtv2}/{jenis}', [TrainerController::class, 'tra_kar'])->name('trainer.training.tra_kar');
    Route::get('/trainer/laporan/rek_tra_print', [TrainerController::class, 'rek_tra_print'])->name('trainer.training.rek_tra_print');
    Route::get('/trainer/laporan/tra_kar_print/{id}/{dtv1}/{dtv2}', [TrainerController::class, 'tra_kar_print'])->name('trainer.training.tra_kar_print');

    Route::get('trainer/ktiga/list', [KtigaUserController::class, 'list'])->name('hr.trainer.ktiga.list');
    Route::get('trainer/ktiga/create', [KtigaUserController::class, 'create'])->name('hr.trainer.ktiga.create');
    Route::post('trainer/ktiga/store', [KtigaUserController::class, 'store'])->name('hr.trainer.ktiga.store');
    Route::get('trainer/ktiga/delete/{id}', [KtigaUserController::class, 'delete'])->name('hr.trainer.ktiga.delete');
    Route::get('trainer/ktiga/edit/{id}', [KtigaUserController::class, 'edit'])->name('hr.trainer.ktiga.edit');
    Route::get('trainer/ktiga/view/{id}', [KtigaUserController::class, 'view'])->name('hr.trainer.ktiga.view');
    Route::post('trainer/ktiga/update/{id}', [KtigaUserController::class, 'update'])->name('hr.trainer.ktiga.update');
    Route::get('trainer/ktiga/print/{id}', [KtigaUserController::class, 'print'])->name('hr.trainer.ktiga.print');

    //PENILAIAN KERJA
    Route::get('/hr/trainer/dashboard/penilaiankerja/index', [PenilaianKerjaControllerTrainer::class, 'index'])->name('tr.hr.penilaiankerja.index');
    Route::get('/hr/trainer/dashboard/penilaiankerja/view/{id}', [PenilaianKerjaControllerTrainer::class, 'view'])->name('tr.hr.penilaiankerja.view');
    Route::get('/hr/trainer/dashboard/penilaiankerja/create', [PenilaianKerjaControllerTrainer::class, 'create'])->name('tr.hr.penilaiankerja.create');
    Route::post('/hr/trainer/dashboard/penilaiankerja/store', [PenilaianKerjaControllerTrainer::class, 'store'])->name('tr.hr.penilaiankerja.store');
    Route::get('/hr/trainer/dashboard/penilaiankerja/edit/{id}', [PenilaianKerjaControllerTrainer::class, 'edit'])->name('tr.hr.penilaiankerja.edit');
    Route::post('/hr/trainer/dashboard/penilaiankerja/update/{id}', [PenilaianKerjaControllerTrainer::class, 'update'])->name('tr.hr.penilaiankerja.update');
    Route::get('/hr/trainer/dashboard/penilaiankerja/delete/{id}', [PenilaianKerjaControllerTrainer::class, 'delete'])->name('tr.hr.penilaiankerja.delete');
    Route::get('/hr/trainer/dashboard/penilaiankerja/print/{id}', [PenilaianKerjaControllerTrainer::class, 'print'])->name('tr.hr.penilaiankerja.print');

    Route::get('/kelengkapanpegawaitr/{no_payroll}', [PenilaianKerjaControllerTrainer::class, 'kelengkapanpegawaitr']);
    Route::get('/kelengkapandataperbagian/{bagian}', [PenilaianKerjaControllerTrainer::class, 'kelengkapandataperbagian']);
    // Route::get('/reqbagian/{bagian}', [PenilaianKerjaController::class, 'reqbagian']);

    Route::get('/hr/trainer/dashboard/penilaiankerjareport/laporan', [PenilaianKerjaControllerTrainer::class, 'laporan'])->name('tr.hr.penilaiankerja.laporan');
    Route::get('/hr/trainer/dashboard/penilaiankerjareport/listLaporan', [PenilaianKerjaControllerTrainer::class, 'listLaporan'])->name('tr.hr.penilaiankerja.listlaporan');
});

// untuk QC
Route::group(['middleware' => ['auth', 'checkrole:6']], function () {
    Route::get('/qc', [QcController::class, 'index'])->name('problemmsn.index');
    Route::get('/qc/dashboard/problemmsn/list', [QcController::class, 'list'])->name('problemmsn.list');
    Route::get('/qc/dashboard/problemmsn/view/{id}', [QcController::class, 'view'])->name('problemmsn.view');
    Route::get('/qc/dashboard/problemmsn/view_d/{id}', [QcController::class, 'view_d'])->name('problemmsn.view_d');
    Route::get('/qc/dashboard/problemmsn/create', [QcController::class, 'create'])->name('problemmsn.create');
    Route::post('/qc/dashboard/problemmsn/store', [QcController::class, 'store'])->name('problemmsn.store');
    Route::get('/qc/dashboard/problemmsn/edit/{id}', [QcController::class, 'edit'])->name('problemmsn.edit');
    Route::post('/qc/dashboard/problemmsn/update/{id}', [QcController::class, 'update'])->name('problemmsn.update');
    Route::get('/qc/dashboard/problemmsn/delete/{id}', [QcController::class, 'delete'])->name('problemmsn.delete');
    Route::delete('/qc/dashboard/problemmsn/delete_d/{id}', [QcController::class, 'delete_d'])->name('problemmsn.delete_d');
    Route::get('/qc/dashboard/problemmsn/print/{id}', [QcController::class, 'print'])->name('problemmsn.print');
    Route::get('/qc/dashboard/problemmsn/print_d/{id}', [QcController::class, 'print_d'])->name('problemmsn.print_d');
    //training
    Route::get('/qc/training/qtrlist', [QcController::class, 'qtrlist'])->name('training.qtrlist');
    Route::get('/qc/training/qtrcreate', [QcController::class, 'qtrcreate'])->name('training.qtrcreate');
    Route::get('/qtrautocomplete', [QcController::class, 'qtrautocomplete'])->name('training.qtrautocomplete');
    Route::post('/qc/training/qtrstore', [QcController::class, 'qtrstore'])->name('training.qtrstore');
    Route::get('/qc/training/qtrview/{id}', [QcController::class, 'qtrview'])->name('training.qtrview');
    Route::get('/qc/training/qtredit/{id}', [QcController::class, 'qtredit'])->name('training.qtredit');
    Route::post('/qc/training/qtrupdate/{id}', [QcController::class, 'qtrupdate'])->name('training.qtrupdate');
    Route::delete('/qc/training/qtrdelete/{id}', [QcController::class, 'qtrdelete'])->name('training.qtrdelete');
    Route::get('/qc/training/qtrdelete/{id}', [QcController::class, 'qtrdelete'])->name('training.qtrdelete');
    Route::delete('/qc/training/qtrdelete_d/{id}', [QcController::class, 'qtrdelete_d'])->name('training.qtrdelete_d');
    Route::get('/qc/training/qtrtrain_trash/{id}', [QcController::class, 'qtrtrain_trash'])->name('training.qtrtrain_trash');
    Route::get('/qc/training/qtrrestore_trash/{id}', [QcController::class, 'qtrrestore_trash'])->name('training.qtrrestore_trash');
    Route::get('/qc/training/qtrrestore_all/{id}', [QcController::class, 'qtrrestore_all'])->name('training.qtrrestore_all');
    Route::get('/qc/training/qtrprint/{id}', [QcController::class, 'qtrprint'])->name('qtrprint');
});
// Op
Route::group(['middleware' => ['auth', 'checkrole:7']], function () {
    Route::get('/produksi', [OpController::class, 'produksi'])->name('op.problemmsn.produksi');
    Route::get('/op', [OpController::class, 'index'])->name('op.problemmsn.index');
    Route::get('/op/dashboard/problemmsn/list', [OpController::class, 'list'])->name('op.problemmsn.list');
    Route::get('/op/dashboard/problemmsn/view_d/{id}', [OpController::class, 'view_d'])->name('op.problemmsn.view_d');
    Route::get('/op/dashboard/problemmsn/create', [OpController::class, 'create'])->name('op.problemmsn.create');
    Route::get('/op/dashboard/problemmsn/print/{id}', [OpController::class, 'print'])->name('op.problemmsn.print');
    Route::get('/op/dashboard/problemmsn/print_d/{id}', [OpController::class, 'print_d'])->name('op.problemmsn.print_d');
});

//UMUM
Route::group(['middleware' => ['auth', 'checkrole:8']], function () {
    Route::get('/umum', [UmumController::class, 'index'])->name('umum.index');
    //Overtime
    Route::get('/umum/dashboard/overt/list', [UmumOverTimeController::class, 'list'])->name('umum.overt.list');
    Route::get('/umum/dashboard/overt/detail/{id}', [UmumOverTimeController::class, 'detail'])->name('umum.overt.detail');
    Route::get('/umum/dashboard/overt/edit/{id}', [UmumOverTimeController::class, 'edit'])->name('umum.overt.edit');
    Route::get('/umum/dashboard/overt/delete/{id}', [UmumOverTimeController::class, 'delete'])->name('umum.overt.delete');
    Route::get('/umum/dashboard/overt/create', [UmumOverTimeController::class, 'create'])->name('umum.overt.create');
    Route::post('/umum/dashboard/overt/store', [UmumOverTimeController::class, 'store'])->name('umum.overt.store');
    Route::post('/umum/dashboard/overt/update/{id}', [UmumOverTimeController::class, 'update'])->name('umum.overt.update');
    Route::get('/umum/dashboard/overt/delete_d/{id}', [UmumOverTimeController::class, 'delete_d'])->name('umum.overt.delete_d');
    Route::get('/umum/dashboard/overt/print/{id}', [UmumOverTimeController::class, 'print'])->name('umum.overt.print');
    //Absen
    Route::get('/umum/dashboard/absen/list', [UmumAbsenController::class, 'list'])->name('umum.absen.list');
    Route::get('/umum/dashboard/absen/detail/{bln}/{thn}/{id}', [UmumAbsenController::class, 'detail'])->name('umum.absen.detail');
    Route::get('/umum/dashboard/absen/edit/{id}', [UmumAbsenController::class, 'edit'])->name('umum.absen.edit');
    Route::get('/umum/dashboard/absen/delete/{id}', [UmumAbsenController::class, 'delete'])->name('umum.absen.delete');
    Route::get('/umum/dashboard/absen/print/{bln}/{thn}/{id}', [UmumAbsenController::class, 'print'])->name('umum.absen.print');
    Route::get('/umum/dashboard/absen/create/{bln}/{thn}', [UmumAbsenController::class, 'create'])->name('umum.absen.create');
    Route::post('/umum/dashboard/absen/store/{bln}/{thn}', [UmumAbsenController::class, 'store'])->name('umum.absen.store');
    Route::post('/umum/dashboard/absen/update/{id}', [UmumAbsenController::class, 'update'])->name('umum.absen.update');
    Route::get('/umum/dashboard/absen/delete_d/{id}', [UmumAbsenController::class, 'delete_d'])->name('umum.absen.delete_d');
    Route::get('/autocompleted_umum', [UmumAbsenController::class, 'autocomplete'])->name('umum.training.autocomplete');
    Route::get('/find_pegawai_umum/{id}', [UmumAbsenController::class, 'find_pegawai'])->name('hr.absen.find_pegawai');
    Route::get('/find_absen_h_umum/{id}', [UmumAbsenController::class, 'find_absen_h'])->name('hr.absen.find_absen_h');

    //REPORT ABSEN
    Route::get('/umum/dashboard/report/reportabsen/index', [umumReportPresensiController::class, 'index'])->name('umum.reportabsen.index');
    Route::get('/umum/dashboard/report/reportabsen/list', [umumReportPresensiController::class, 'list'])->name('umum.reportabsen.list');
    Route::get('/umum/dashboard/report/reportabsen/list_print', [umumReportPresensiController::class, 'list_print'])->name('umum.reportabsen.list_print');
    Route::get('/umum/dashboard/report/reportabsen/uraianlembur', [umumReportPresensiController::class, 'uraianlembur'])->name('umum.reportabsen.uraianlembur');
    Route::get('/umum/dashboard/report/reportabsen/uraianlembur_print', [umumReportPresensiController::class, 'uraianlembur_print'])->name('umum.reportabsen.uraianlembur_print');
    Route::get('/umum/dashboard/report/reportabsen/rekapgaji', [umumReportPresensiController::class, 'rekapgaji'])->name('umum.reportabsen.rekapgaji');
    Route::get('/umum/dashboard/report/reportabsen/rekapgaji_print', [umumReportPresensiController::class, 'rekapgaji_print'])->name('umum.reportabsen.rekapgaji_print');
    Route::get('/umum/dashboard/report/reportabsen/rekapgaji_excel', [umumReportPresensiController::class, 'rekapgaji_excel'])->name('umum.reportabsen.rekapgaji_excel');

    //REPORT CUTI
    Route::get('/umum/dashboard/report/reportcuti/index', [umumReportCutiController::class, 'index'])->name('umum.reportcuti.index');
    Route::get('/umum/dashboard/report/reportcuti/list', [umumReportCutiController::class, 'list'])->name('umum.reportcuti.list');
    Route::match(['get', 'post'], '/umum/dashboard/report/reportcuti/print', [umumReportCutiController::class, 'print'])->name('umum.reportcuti.print');

    // REPORT ICB
    Route::get('/umum/dashboard/report/reporticb/index', [umumReportIcbController::class, 'index'])->name('umum.reporticb.index');
    Route::get('/umum/dashboard/report/reporticb/list', [umumReportIcbController::class, 'list'])->name('umum.reporticb.list');
    Route::get('/umum/dashboard/report/reporticb/print', [umumReportIcbController::class, 'print'])->name('umum.reporticb.print');

    // REPORT IPA MDT
    Route::get('/umum/dashboard/report/reportipamdt/index', [umumReportIpaMdtController::class, 'index'])->name('umum.reportipamdt.index');
    Route::get('/umum/dashboard/report/reportipamdt/list', [umumReportIpaMdtController::class, 'list'])->name('umum.reportipamdt.list');
    Route::get('/umum/dashboard/report/reportipamdt/print', [umumReportIpaMdtController::class, 'print'])->name('umum.reportipamdt.print');

    // REPORT IPA MDT KTMH
    Route::get('/umum/dashboard/report/reporttlak/index', [umumReportTlAkController::class, 'index'])->name('umum.reporttlak.index');
    Route::get('/umum/dashboard/report/reporttlak/list', [umumReportTlAkController::class, 'list'])->name('umum.reporttlak.list');
    Route::get('/umum/dashboard/report/reporttlak/print', [umumReportTlAkController::class, 'print'])->name('umum.reporttlak.print');

    // REPORT LKK
    Route::get('/umum/dashboard/report/reportlkk/index', [umumReportLkkController::class, 'index'])->name('umum.reportlkk.index');
    Route::get('/umum/dashboard/report/reportlkk/list', [umumReportLkkController::class, 'list'])->name('umum.reportlkk.list');
    Route::get('/umum/dashboard/report/reportlkk/print', [umumReportLkkController::class, 'print'])->name('umum.reportlkk.print');

    // prime
    Route::get('/pegawailengkap', [primeController::class, 'pegawailengkap']);

    // Surat Cuti
    Route::get('/umum/dashboard/srtcuti/list', [umumSrtCutiController::class, 'list'])->name('umum.srtcuti.list');
    Route::get('/umum/dashboard/srtcuti/create', [umumSrtCutiController::class, 'create'])->name('umum.srtcuti.create');
    Route::post('/umum/dashboard/srtcuti/store', [umumSrtCutiController::class, 'store'])->name('umum.srtcuti.store');
    Route::get('/umum/dashboard/srtcuti/edit/{id}', [umumSrtCutiController::class, 'edit'])->name('umum.srtcuti.edit');
    Route::get('/umum/dashboard/srtcuti/delete/{id}', [umumSrtCutiController::class, 'delete'])->name('umum.srtcuti.delete');
    Route::get('/umum/dashboard/srtcuti/print/{id}', [umumSrtCutiController::class, 'print'])->name('umum.srtcuti.print');
    Route::post('/umum/dashboard/srtcuti/update/{id}', [umumSrtCutiController::class, 'update'])->name('umum.srtcuti.update');
});

Route::middleware([])->group(function () {
    Route::get('/api/dashboard/overt/list', [apiController::class, 'list'])->name('umum.overt.list');
    Route::get('/api/data/karyawan', [apiController::class, 'karyawan'])->name('umum.overt.list');
});
