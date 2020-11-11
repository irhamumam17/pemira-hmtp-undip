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
Route::group(['middleware' => 'admin'],function(){
    Route::get('/admin/home',[App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
});
Route::group(['middleware' => 'mahasiswa'],function(){
    Route::get('/mahasiswa/pemilihan/vote',[App\Http\Controllers\MahasiswaController::class, 'pemilihan'])->name('mahasiswa.pemilihan');
    Route::get('/mahasiswa/pemilihan/get-calon',[App\Http\Controllers\MahasiswaController::class, 'get_calon'])->name('mahasiswa.get_calon');
    Route::get('/mahasiswa/pemilihan/identification',[App\Http\Controllers\MahasiswaController::class, 'identification_view'])->name('mahasiswa.identification_view');
    Route::post('/mahasiswa/pemilihan/post',[App\Http\Controllers\MahasiswaController::class, 'pemilihan_temporary_post'])->name('mahasiswa.pemilihan_temporary_post');
    Route::post('/mahasiswa/pemilihan/identification/upload',[App\Http\Controllers\MahasiswaController::class, 'identification_upload'])->name('mahasiswa.identification_upload');
    Route::post('/mahasiswa/pemilihan/identification/post',[App\Http\Controllers\MahasiswaController::class, 'identification_post'])->name('mahasiswa.identification_post');
    Route::get('/mahasiswa/pemilihan/review',[App\Http\Controllers\MahasiswaController::class, 'review'])->name('mahasiswa.review');
    Route::get('/mahasiswa/pemilihan/revote',[App\Http\Controllers\MahasiswaController::class, 'revote'])->name('mahasiswa.revote');
    Route::get('/mahasiswa/pemilihan/post/confirm',[App\Http\Controllers\MahasiswaController::class, 'pemilihan_post'])->name('mahasiswa.pemilihan_post');
    Route::get('/mahasiswa/logout',[App\Http\Controllers\AuthenticationController::class,'mahasiswa_logout'])->name('mahasiswa.logout');
    Route::get('/coba',[App\Http\Controllers\MahasiswaController::class,'coba']);
});
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
