<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\AuthenticationController::class,'index'])->name('home');

Auth::routes();
Route::group(['middleware' => 'guest'],function(){
    Route::get('/mahasiswa/login',[App\Http\Controllers\AuthenticationController::class, 'mahasiswa_login_view'])->name('mahasiswa.login');
    Route::post('/mahasiswa/login',[App\Http\Controllers\AuthenticationController::class, 'mahasiswa_login_post'])->middleware('voters_throttle')->name('mahasiswa.login');
    Route::get('/admin/login',[App\Http\Controllers\AuthenticationController::class, 'admin_login_view'])->name('admin.login');
    Route::post('/admin/login',[App\Http\Controllers\AuthenticationController::class, 'admin_login_post'])->name('admin.login');
});
Route::group(['prefix'=>'admin','as'=>'admin.','middleware' => 'admin'],function(){
    //home
    Route::get('home',[App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('home/get-data',[App\Http\Controllers\DashboardController::class, 'getDataPemilihan'])->name('dashboard.get_data');
    //admin
    Route::resource('admin',App\Http\Controllers\AdminController::class);
    Route::get('get-admin',[App\Http\Controllers\AdminController::class, 'get_admin'])->name('admin.get_data');
    //mahasiswa
    Route::resource('mahasiswa',App\Http\Controllers\MahasiswaController::class);
    Route::get('get-mahasiswa',[App\Http\Controllers\MahasiswaController::class, 'get_mahasiswa'])->name('mahasiswa.get_data');
    Route::post('/mahasiswa/import',[App\Http\Controllers\MahasiswaController::class,'import'])->name('mahasiswa.import');
    Route::put('/mahasiswa/{id}/reset',[App\Http\Controllers\MahasiswaController::class,'reset_session'])->name('mahasiswa.reset');
    Route::post('/mahasiswa/send-account',[App\Http\Controllers\MahasiswaController::class,'sendEmailAccount'])->name('mahasiswa.send_account');
    //paslon
    Route::resource('paslon',App\Http\Controllers\PaslonController::class);
    Route::get('get-paslon',[App\Http\Controllers\PaslonController::class, 'getData'])->name('paslon.get_data');
    Route::get('get-calon-ketua',[App\Http\Controllers\PaslonController::class, 'getCalonKetua'])->name('paslon.get_calon_ketua');
    Route::post('paslon/get-calon-wakil',[App\Http\Controllers\PaslonController::class, 'getCalonWakil'])->name('paslon.get_calon_wakil');
    Route::post('/paslon/{id}',[App\Http\Controllers\PaslonController::class, 'update'])->name('paslon.update2');
    //sesi
    Route::get('get-sesi',[App\Http\Controllers\AdminController::class, 'dashboard'])->name('get_sesi');

    //data pemilihan
    Route::get('data-pemilihan',[App\Http\Controllers\PemilihanController::class,'dataPemilihan'])->name('data_pemilihan.index');
    Route::get('data-pemilihan/get-data',[App\Http\Controllers\PemilihanController::class,'getDataPemilihan'])->name('data_pemilihan.get_data');
    Route::put('data-pemilihan/valid/{id}',[App\Http\Controllers\PemilihanController::class,'validVote'])->name('data_pemilihan.valid');
    Route::put('data-pemilihan/invalid/{id}',[App\Http\Controllers\PemilihanController::class,'invalidVote'])->name('data_pemilihan.invalid');

    //report
    Route::get('laporan',[App\Http\Controllers\ReportController::class,'index'])->name('report.index');
    Route::get('laporan/get-data',[App\Http\Controllers\ReportController::class,'getData'])->name('report.get_data');
    Route::get('laporan/get-perolehan-suara',[App\Http\Controllers\ReportController::class,'getPerolehanSuara'])->name('report.get_perolehan_suara');
    // cek
    Route::get('/logout',[App\Http\Controllers\AuthenticationController::class,'admin_logout'])->name('logout');
});
Route::group(['prefix'=>'mahasiswa','as'=>'mahasiswa.','middleware' => 'mahasiswa'],function(){
    Route::get('/pemilihan/index',[App\Http\Controllers\PemilihanController::class, 'index'])->name('pemilihan');
    Route::get('/pemilihan/get-calon',[App\Http\Controllers\PemilihanController::class, 'get_calon'])->name('get_calon');
    Route::get('/pemilihan/identification',[App\Http\Controllers\PemilihanController::class, 'identification_view'])->name('identification_view');
    Route::post('/pemilihan/post',[App\Http\Controllers\PemilihanController::class, 'pemilihan_temporary_post'])->name('pemilihan_temporary_post');
    Route::post('/pemilihan/identification/upload',[App\Http\Controllers\PemilihanController::class, 'identification_upload'])->name('identification_upload');
    Route::post('/pemilihan/identification/post',[App\Http\Controllers\PemilihanController::class, 'identification_post'])->name('identification_post');
    Route::get('/pemilihan/review',[App\Http\Controllers\PemilihanController::class, 'review'])->name('review');
    Route::get('/pemilihan/revote',[App\Http\Controllers\PemilihanController::class, 'revote'])->name('revote');
    Route::get('/pemilihan/post/confirm',[App\Http\Controllers\PemilihanController::class, 'pemilihan_post'])->name('pemilihan_post');
    Route::get('/logout',[App\Http\Controllers\AuthenticationController::class,'mahasiswa_logout'])->name('logout');
});
Route::get('/quickcount', [App\Http\Controllers\PemilihanController::class, 'quickcount'])->name('quickcount.index');
Route::post('/quickcount/get-data', [App\Http\Controllers\PemilihanController::class, 'getDataQuickcount'])->name('quickcount.get_data');
