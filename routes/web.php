<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\WilayahController;
use App\Http\Controllers\AccountManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OperationalController;
use App\Http\Controllers\ScreeningController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::redirect('/', '/individu');

Route::prefix('api/wilayah')->group(function () {
    Route::get('/provinces', [WilayahController::class, 'provinces'])->name('api.wilayah.provinces');
    Route::get('/regencies/{provinceId}', [WilayahController::class, 'regencies'])->name('api.wilayah.regencies');
    Route::get('/districts/{regencyId}', [WilayahController::class, 'districts'])->name('api.wilayah.districts');
    Route::get('/villages/{districtId}', [WilayahController::class, 'villages'])->name('api.wilayah.villages');
});

Route::middleware('auth')->group(function () {
    Route::get('/api/respondent/check-nik', [ScreeningController::class, 'checkNik'])->name('api.respondent.check_nik');
    Route::get('/api/respondent/search-candidates', [ScreeningController::class, 'searchCandidates'])->name('api.respondent.search_candidates');
    Route::get('/individu', [OperationalController::class, 'individu'])->name('individu.index');
    Route::post('/individu/{session}/konfirmasi-hadir', [OperationalController::class, 'confirmAttendance'])->name('individu.confirm');
    Route::post('/individu/{session}/ganti-tanggal', [OperationalController::class, 'changeDate'])->name('individu.change_date');

    Route::get('/pelayanan', [OperationalController::class, 'pelayanan'])->name('pelayanan.index');
    Route::post('/pelayanan/{session}/mulai', [OperationalController::class, 'startService'])->name('pelayanan.start');
    Route::post('/pelayanan/{session}/selesai', [OperationalController::class, 'finishService'])->name('pelayanan.finish');
    Route::post('/pelayanan/{session}/kirim-rapor', [OperationalController::class, 'sendReport'])->name('pelayanan.send_report');
    Route::get('/pelayanan/{session}/rapor', [OperationalController::class, 'report'])->name('pelayanan.report');
    Route::get('/pelayanan/{session}/rapor/pdf', [OperationalController::class, 'reportPdf'])->name('pelayanan.report_pdf');
    Route::get('/pelayanan/{session}/detail', [OperationalController::class, 'serviceDetail'])->name('pelayanan.detail');
    Route::post('/pelayanan/{session}/detail', [OperationalController::class, 'saveServiceDetail'])->name('pelayanan.detail.save');
    Route::post('/pelayanan/{session}/detail-data', [OperationalController::class, 'updateDetailData'])->name('pelayanan.detail.update_data');
    Route::get('/pelayanan/{session}/layanan/{serviceKey}/input', [OperationalController::class, 'nakesInputForm'])->name('pelayanan.nakes.input');
    Route::post('/pelayanan/{session}/layanan/{serviceKey}/input', [OperationalController::class, 'saveNakesInputForm'])->name('pelayanan.nakes.input.save');

    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/ubah-password', [ProfileController::class, 'passwordForm'])->name('profile.password.form');
    Route::post('/ubah-password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('/manajemen-akun', [AccountManagementController::class, 'index'])->name('accounts.index');
    Route::post('/manajemen-akun', [AccountManagementController::class, 'store'])->name('accounts.store');
    Route::post('/manajemen-akun/{user}/update', [AccountManagementController::class, 'update'])->name('accounts.update');
    Route::post('/manajemen-akun/{user}/delete', [AccountManagementController::class, 'destroy'])->name('accounts.destroy');

    Route::prefix('dashboard')->middleware('dashboard.access')->group(function () {
        Route::get('/overview', [DashboardController::class, 'overview'])->name('dashboard.overview');
        Route::get('/faktor-risiko', [DashboardController::class, 'riskFactors'])->name('dashboard.risk_factors');
        Route::get('/kesehatan-mental', [DashboardController::class, 'mentalHealth'])->name('dashboard.mental');
        Route::get('/perilaku-merokok', [DashboardController::class, 'smoking'])->name('dashboard.smoking');
        Route::get('/aktivitas-fisik', [DashboardController::class, 'physicalActivity'])->name('dashboard.activity');
        Route::get('/risk-scoring', [DashboardController::class, 'riskScoring'])->name('dashboard.risk_scoring');
    });

    Route::get('/skrining/create', [ScreeningController::class, 'create'])->name('screening.create');
    Route::post('/skrining/langkah-1', [ScreeningController::class, 'storeStep1'])->name('screening.step1');
    Route::get('/skrining/kuesioner/{kategori}', [ScreeningController::class, 'wizard'])->name('screening.wizard');
    Route::post('/skrining/kuesioner/{kategori}', [ScreeningController::class, 'storeWizard'])->name('screening.wizard.store');
});
