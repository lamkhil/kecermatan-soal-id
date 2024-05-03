<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\AdminController;

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

Route::get('/', function () {
    return redirect('/login');
});

//AUTH

Route::get('/login',[AuthController::class,'login'])->name('login');
Route::post('/login',[AuthController::class,'auth']);
Route::get('/register',[AuthController::class,'register']);
Route::post('/register',[AuthController::class,'saveRegister']);
Route::get('/logout',[AuthController::class,'logout']);








//MEMBER

Route::get('/member/dashboard',[WebController::class,'dashboard'])->middleware('auth','member');

Route::get('/member/profile',[WebController::class,'profile'])->middleware('auth','member');
Route::put('/member/profile',[WebController::class,'saveProfile'])->middleware('auth','member');
Route::get('/list/tryout',[WebController::class,'listTryout'])->middleware('auth','member');
Route::get('/list/paket',[WebController::class,'listPaket'])->middleware('auth','member');
Route::post('/list/paket/detail',[WebController::class,'listPaketDetail'])->middleware('auth','member');
Route::post('/list/paket',[WebController::class,'upgradePaket'])->middleware('auth','member');
Route::post('/view/soal',[WebController::class,'viewSoal'])->middleware('auth','member');
Route::get('/soal/kerjakan/{id}',[WebController::class,'kerjakanSoal'])->middleware('auth','member');
Route::post('/get/soalbaris/{id}/{soalusers}',[WebController::class,'getSoalBaris'])->middleware('auth','member');
Route::post('/soal/jawab/{id}',[WebController::class,'jawabSoal'])->middleware('auth','member');
Route::get('/riwayat/tryout',[WebController::class,'riwayatTryout'])->middleware('auth','member');
Route::get('/riwayat/tryout/{id}',[WebController::class,'detailTryout'])->middleware('auth','member');



//ADMIN

Route::get('/admin/dashboard',[AdminController::Class,'dashboard'])->middleware('auth','admin');

Route::get('/profile',[AdminController::class,'profile'])->middleware('auth','admin');
Route::put('/profile',[AdminController::class,'saveProfile'])->middleware('auth','admin');

Route::get('/member',[AdminController::class,'member'])->middleware('auth','admin');
Route::post('/member',[AdminController::class,'saveMember'])->middleware('auth','admin');
Route::delete('/member',[AdminController::class,'deleteMember'])->middleware('auth','admin');
Route::post('/member/detail',[AdminController::class,'detailMember'])->middleware('auth','admin');
Route::put('/member',[AdminController::class,'editMember'])->middleware('auth','admin');
Route::post('/member/extend',[AdminController::class,'extendMember'])->middleware('auth','admin');

// Route::get('/paket',[AdminController::class,'paket'])->middleware('auth','admin');
// Route::post('/paket',[AdminController::class,'savePaket'])->middleware('auth','admin');
// Route::delete('/paket',[AdminController::class,'deletePaket'])->middleware('auth','admin');
// Route::post('/paket/detail',[AdminController::class,'detailPaket'])->middleware('auth','admin');
// Route::put('/paket',[AdminController::class,'editPaket'])->middleware('auth','admin');

Route::get('/paket/bundle',[AdminController::class,'paketBundle'])->middleware('auth','admin');
Route::post('/paket/bundle',[AdminController::class,'savePaketBundle'])->middleware('auth','admin');
Route::delete('/paket/bundle',[AdminController::class,'deletePaketBundle'])->middleware('auth','admin');
Route::post('/paket/bundle/detail',[AdminController::class,'detailPaketBundle'])->middleware('auth','admin');
Route::put('/paket/bundle',[AdminController::class,'editPaketBundle'])->middleware('auth','admin');



Route::get('/pembayaran',[AdminController::class,'pembayaran'])->middleware('auth','admin');
Route::delete('/pembayaran',[AdminController::class,'deletePembayaran'])->middleware('auth','admin');
Route::put('/pembayaran',[AdminController::class,'statusPembayaran'])->middleware('auth','admin');

Route::get('/bab/soal',[AdminController::class,'babSoal'])->middleware('auth','admin');
Route::post('/bab/soal',[AdminController::class,'saveBabSoal'])->middleware('auth','admin');
Route::delete('/bab/soal',[AdminController::class,'deleteBabSoal'])->middleware('auth','admin');
Route::post('/bab/soal/detail',[AdminController::class,'detailBabSoal'])->middleware('auth','admin');
Route::put('/bab/soal',[AdminController::class,'editBabSoal'])->middleware('auth','admin');

Route::get('/list/soal',[AdminController::class,'listSoal'])->middleware('auth','admin');
Route::post('/list/soal',[AdminController::class,'saveSoal'])->middleware('auth','admin');
Route::delete('/list/soal',[AdminController::class,'deleteSoal'])->middleware('auth','admin');
Route::post('/list/soal/detail',[AdminController::class,'detailSoal'])->middleware('auth','admin');
Route::put('/list/soal',[AdminController::class,'editSoal'])->middleware('auth','admin');

Route::get('/list/soal/detail/{id}',[AdminController::class,'viewSoal'])->middleware('auth','admin');
Route::post('/list/soal/detail/{id}',[AdminController::class,'saveSoalBaris'])->middleware('auth','admin');
Route::delete('/list/soal/detail/{id}',[AdminController::class,'deleteSoalBaris'])->middleware('auth','admin');

Route::get('/setting/web',[AdminController::class,'settingWeb'])->middleware('auth','admin');
Route::post('/setting/web',[AdminController::class,'SaveSettingWeb'])->middleware('auth','admin');