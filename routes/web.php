<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginSecurityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnggaranKasController;
use App\Http\Controllers\ApbdesController;
use App\Http\Controllers\DanasController;
use App\Http\Controllers\DesasController;
use App\Http\Controllers\DokumensController;
use App\Http\Controllers\NoRekeningsController;
use App\Http\Controllers\RealisasiAnggaransController;
use App\Http\Controllers\Sp2dsController;
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


Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware(['auth', 'XSS', '2fa',]);

Route::post('/chart', [HomeController::class, 'chart'])->name('get.chart.data')->middleware(['auth', 'XSS',]);

Route::get('notification', [HomeController::class, 'notification']);


Route::group(['middleware' => ['auth', 'XSS']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('permission', PermissionController::class);
    Route::resource('modules', ModulController::class);
});

Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('users.destroy')->middleware(['auth', 'XSS']);

Route::post('/role/{id}', [RoleController::class, 'assignPermission'])->name('roles_permit')->middleware(['auth', 'XSS']);

Route::group(
    ['middleware' => ['auth', 'XSS']],
    function () {
        Route::get('setting/email-setting', [SettingController::class, 'getmail'])->name('settings.getmail');
        Route::post('setting/email-settings_store', [SettingController::class, 'saveEmailSettings'])->name('settings.emails');

        Route::get('setting/datetime', [SettingController::class, 'getdate'])->name('datetime');
        Route::post('setting/datetime-settings_store', [SettingController::class, 'saveSystemSettings'])->name('settings.datetime');

        Route::get('setting/logo', [SettingController::class, 'getlogo'])->name('getlogo');
        Route::post('setting/logo_store', [SettingController::class, 'store'])->name('settings.logo');
        Route::resource('settings', SettingController::class);

        Route::get('test-mail', [SettingController::class, 'testMail'])->name('test.mail');
        Route::post('test-mail', [SettingController::class, 'testSendMail'])->name('test.send.mail');
    }
);

Route::get('profile', [UserController::class, 'profile'])->name('profile')->middleware(['auth', 'XSS']);

Route::post('edit-profile', [UserController::class, 'editprofile'])->name('update.profile')->middleware(['auth', 'XSS']);

Route::group(
    ['middleware' => ['auth', 'XSS']],
    function () {
        Route::get('change-language/{lang}', [LanguageController::class, 'changeLanquage'])->name('change.language');
        Route::get('manage-language/{lang}', [LanguageController::class, 'manageLanguage'])->name('manage.language');
        Route::post('store-language-data/{lang}', [LanguageController::class, 'storeLanguageData'])->name('store.language.data');
        Route::get('create-language', [LanguageController::class, 'createLanguage'])->name('create.language');
        Route::post('store-language', [LanguageController::class, 'storeLanguage'])->name('store.language');
        Route::delete('/lang/{lang}', [LanguageController::class, 'destroyLang'])->name('lang.destroy');
        Route::get('language', [LanguageController::class, 'index'])->name('index');
    }
);

Route::get('generator_builder', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@builder')->name('io_generator_builder')->middleware(['auth', 'XSS',]);

Route::get('field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@fieldTemplate')->name('io_field_template')->middleware(['auth', 'XSS',]);

Route::get('relation_field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@relationFieldTemplate')->name('io_relation_field_template')->middleware(['auth', 'XSS',]);

Route::post('generator_builder/generate', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generate')->name('io_generator_builder_generate')->middleware(['auth', 'XSS',]);

Route::post('generator_builder/rollback', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@rollback')->name('io_generator_builder_rollback')->middleware(['auth', 'XSS',]);

Route::post(
    'generator_builder/generate-from-file',
    '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generateFromFile'
)->name('io_generator_builder_generate_from_file')->middleware(['auth', 'XSS',]);

Route::group(['prefix' => '2fa'], function () {
    Route::get('/', [UserController::class, 'profile'])->name('2fa')->middleware(['auth', 'XSS',]);
    Route::post('/generateSecret', [LoginSecurityController::class, 'generate2faSecret'])->name('generate2faSecret')->middleware(['auth', 'XSS',]);
    Route::post('/enable2fa', [LoginSecurityController::class, 'enable2fa'])->name('enable2fa')->middleware(['auth', 'XSS',]);
    Route::post('/disable2fa', [LoginSecurityController::class, 'disable2fa'])->name('disable2fa')->middleware(['auth', 'XSS',]);

    // 2fa middleware
    Route::post('/2faVerify', function () {
        return redirect(URL()->previous());
    })->name('2faVerify')->middleware('2fa');
});

Route::group(
    ['middleware' => ['auth', 'XSS']],
    function () {
        Route::get('realisasiAnggarans', [RealisasiAnggaranController::class, 'index'])->name('realisasiAnggarans.index');
        Route::get('realisasiAnggarans/create', [RealisasiAnggaranController::class, 'create'])->name('realisasiAnggarans.create');
        Route::post('realisasiAnggarans/store', [RealisasiAnggaranController::class, 'store'])->name('realisasiAnggarans.store');
        Route::get('realisasiAnggarans/{id}/edit', [RealisasiAnggaranController::class, 'edit'])->name('realisasiAnggarans.edit');
        Route::put('realisasiAnggarans/{id}', [RealisasiAnggaranController::class, 'update'])->name('realisasiAnggarans.update');
        Route::delete('realisasiAnggarans/{id}', [RealisasiAnggaranController::class, 'destroy'])->name('realisasiAnggarans.destroy');
        Route::get('realisasiAnggarans/{id}', [RealisasiAnggaranController::class, 'show'])->name('realisasiAnggarans.show');
    }
);

// Route::group(
//     ['middleware' => ['auth', 'XSS']],
//     function () {
//         Route::get('anggaranKas', [AnggaranKasController::class, 'index'])->name('anggaranKas.index');
//         Route::get('anggaranKas/create', [AnggaranKasController::class, 'create'])->name('anggaranKas.create');
//         Route::post('anggaranKas/store', [AnggaranKasController::class, 'store'])->name('anggaranKas.store');
//         Route::get('anggaranKas/{id}/edit', [AnggaranKasController::class, 'edit'])->name('anggaranKas.edit');
//         Route::put('anggaranKas/{id}', [AnggaranKasController::class, 'update'])->name('anggaranKas.update');
//         Route::delete('anggaranKas/{id}', [AnggaranKasController::class, 'destroy'])->name('anggaranKas.destroy');
//         Route::get('anggaranKas/{id}', [AnggaranKasController::class, 'show'])->name('anggaranKas.show');
//     }
// );


Route::resource('noRekenings', NoRekeningsController::class);



// Anggaran Kas Routes

Route::resource('anggaran-kas', AnggaranKasController::class);


// APBDes Routes
Route::resource('apbdes', ApbdesController::class);

// Danas Routes
Route::resource('danas', DanasController::class);

// Desas Routes
Route::put('desas/{desa}', [DesaController::class, 'update'])->name('desas.update');


// Dokumens Routes
Route::resource('dokumens', DokumensController::class);

// No Rekenings Routes
Route::resource('no_rekenings', NoRekeningsController::class);

// Realisasi Anggarans Routes
Route::resource('realisasi_anggarans', RealisasiAnggaransController::class);

// SP2Ds Routes
Route::resource('sp2ds', Sp2dsController::class);

// Route::group(
//     ['middleware' => ['auth', 'XSS']],
//     function () {
//         Route::get('apbdes', [ApbdesController::class, 'index'])->name('apbdes.index');
//         Route::get('apbdes/create', [ApbdesController::class, 'create'])->name('apbdes.create');
//         Route::post('apbdes/store', [ApbdesController::class, 'store'])->name('apbdes.store');
//         Route::get('apbdes/{id}/edit', [ApbdesController::class, 'edit'])->name('apbdes.edit');
//         Route::put('apbdes/{id}', [ApbdesController::class, 'update'])->name('apbdes.update');
//         Route::delete('apbdes/{id}', [ApbdesController::class, 'destroy'])->name('apbdes.destroy');
//         Route::get('apbdes/{id}', [ApbdesController::class, 'show'])->name('apbdes.show');
//     }
// );

// Route::group(
//     ['middleware' => ['auth', 'XSS']],
//     function () {
//         Route::get('danas', [DanaController::class, 'index'])->name('danas.index');
//         Route::get('danas/create', [DanaController::class, 'create'])->name('danas.create');
//         Route::post('danas/store', [DanaController::class, 'store'])->name('danas.store');
//         Route::get('danas/{id}/edit', [DanaController::class, 'edit'])->name('danas.edit');
//         Route::put('danas/{id}', [DanaController::class, 'update'])->name('danas.update');
//         Route::delete('danas/{id}', [DanaController::class, 'destroy'])->name('danas.destroy');
//         Route::get('danas/{id}', [DanaController::class, 'show'])->name('danas.show');
//     }
// );

// Route::group(
//     ['middleware' => ['auth', 'XSS']],
//     function () {
//         Route::get('desas', [DesaController::class, 'index'])->name('desas.index');
//         Route::get('desas/create', [DesaController::class, 'create'])->name('desas.create');
//         Route::post('desas/store', [DesaController::class, 'store'])->name('desas.store');
//         Route::get('desas/{id}/edit', [DesaController::class, 'edit'])->name('desas.edit');
//         Route::put('desas/{id}', [DesaController::class, 'update'])->name('desas.update');
//         Route::delete('desas/{id}', [DesaController::class, 'destroy'])->name('desas.destroy');
//         Route::get('desas/{id}', [DesaController::class, 'show'])->name('desas.show');
//     }
// );

// Route::group(
//     ['middleware' => ['auth', 'XSS']],
//     function () {
//         Route::get('dokumens', [DokumenController::class, 'index'])->name('dokumens.index');
//         Route::get('dokumens/create', [DokumenController::class, 'create'])->name('dokumens.create');
//         Route::post('dokumens/store', [DokumenController::class, 'store'])->name('dokumens.store');
//         Route::get('dokumens/{id}/edit', [DokumenController::class, 'edit'])->name('dokumens.edit');
//         Route::put('dokumens/{id}', [DokumenController::class, 'update'])->name('dokumens.update');
//         Route::delete('dokumens/{id}', [DokumenController::class, 'destroy'])->name('dokumens.destroy');
//         Route::get('dokumens/{id}', [DokumenController::class, 'show'])->name('dokumens.show');
//     }
// );

// Route::group(
//     ['middleware' => ['auth', 'XSS']],
//     function () {
//         Route::get('noRekenings', [NoRekeningController::class, 'index'])->name('noRekenings.index');
//         Route::get('noRekenings/create', [NoRekeningController::class, 'create'])->name('noRekenings.create');
//         Route::post('noRekenings/store', [NoRekeningController::class, 'store'])->name('noRekenings.store');
//         Route::get('noRekenings/{id}/edit', [NoRekeningController::class, 'edit'])->name('noRekenings.edit');
//         Route::put('noRekenings/{id}', [NoRekeningController::class, 'update'])->name('noRekenings.update');
//         Route::delete('noRekenings/{id}', [NoRekeningController::class, 'destroy'])->name('noRekenings.destroy');
//         Route::get('noRekenings/{id}', [NoRekeningController::class, 'show'])->name('noRekenings.show');
//     }
// );

// Route::group(
//     ['middleware' => ['auth', 'XSS']],
//     function () {
//         Route::get('sp2ds', [Sp2dController::class, 'index'])->name('sp2ds.index');
//         Route::get('sp2ds/create', [Sp2dController::class, 'create'])->name('sp2ds.create');
//         Route::post('sp2ds/store', [Sp2dController::class, 'store'])->name('sp2ds.store');
//         Route::get('sp2ds/{id}/edit', [Sp2dController::class, 'edit'])->name('sp2ds.edit');
//         Route::put('sp2ds/{id}', [Sp2dController::class, 'update'])->name('sp2ds.update');
//         Route::delete('sp2ds/{id}', [Sp2dController::class, 'destroy'])->name('sp2ds.destroy');
//         Route::get('sp2ds/{id}', [Sp2dController::class, 'show'])->name('sp2ds.show');
//     }
// );







































Route::resource('tests', App\Http\Controllers\TestController::class)->middleware(['auth', 'XSS']);






















































































































































































































































































































































Route::resource('anggaranKas', App\Http\Controllers\AnggaranKasController::class);


Route::resource('apbdes', App\Http\Controllers\ApbdesController::class);


Route::resource('danas', App\Http\Controllers\DanaController::class);


Route::resource('desas', App\Http\Controllers\DesaController::class);


Route::resource('dokumens', App\Http\Controllers\DokumenController::class);


Route::resource('noRekenings', App\Http\Controllers\NoRekeningController::class);


Route::resource('realisasiAnggarans', App\Http\Controllers\RealisasiAnggaranController::class);


Route::resource('sp2ds', App\Http\Controllers\Sp2dController::class);


Route::resource('klasifikasiBelanjas', App\Http\Controllers\klasifikasi_belanjaController::class);
