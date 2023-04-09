<?php

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

use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\SliderController;
use Illuminate\Support\Facades\Route;
use Modules\Catalogs\Http\Controllers\CatalogsController;
use Modules\Catalogs\Http\Controllers\MainCatalogsController;

/*
 * ADMIN ROUTES
 */
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], static function () {

    /* Main Catalogs */
    Route::group(['prefix' => 'catalogs/main'], static function () {
        Route::get('/', [MainCatalogsController::class, 'index'])->name('admin.catalogs.main.index');
        Route::get('create', [MainCatalogsController::class, 'create'])->name('admin.catalogs.main.create');
        Route::post('store', [MainCatalogsController::class, 'store'])->name('admin.catalogs.main.store');

        Route::group(['prefix' => 'multiple'], static function () {
            Route::get('active/{active}', [MainCatalogsController::class, 'activeMultiple'])->name('admin.catalogs.main.active-multiple');
            Route::get('delete', [MainCatalogsController::class, 'deleteMultiple'])->name('admin.catalogs.main.delete-multiple');
        });

        Route::group(['prefix' => '{id}'], static function () {
            Route::get('edit', [MainCatalogsController::class, 'edit'])->name('admin.catalogs.main.edit');
            Route::post('update', [MainCatalogsController::class, 'update'])->name('admin.catalogs.main.update');
            Route::get('delete', [MainCatalogsController::class, 'delete'])->name('admin.catalogs.main.delete');
            Route::get('show', [MainCatalogsController::class, 'show'])->name('admin.catalogs.main.show');
            Route::get('/active/{active}', [MainCatalogsController::class, 'active'])->name('admin.catalogs.main.changeStatus');
            Route::get('position/up', [MainCatalogsController::class, 'positionUp'])->name('admin.catalogs.main.position-up');
            Route::get('position/down', [MainCatalogsController::class, 'positionDown'])->name('admin.catalogs.main.position-down');
        });
    });

    /* Catalogs manage */
    Route::group(['prefix' => 'catalogs/manage'], static function () {
        Route::get('/', [CatalogsController::class, 'index'])->name('admin.catalogs.manage.index');
        Route::post('/get-path', [CatalogsController::class, 'getEncryptedPath'])->name('admin.catalogs.manage.get-path');
        Route::get('/load-catalog/{path}', [CatalogsController::class, 'loadCatalogPage'])->name('admin.catalogs.manage.load-catalog');
        Route::get('/create', [CatalogsController::class, 'create'])->name('admin.catalogs.manage.create');
        Route::post('/store', [CatalogsController::class, 'store'])->name('admin.catalogs.manage.store');

        Route::group(['prefix' => 'multiple'], static function () {
            Route::get('active/{active}', [CatalogsController::class, 'activeMultiple'])->name('admin.catalogs.manage.active-multiple');
            Route::get('delete', [CatalogsController::class, 'deleteMultiple'])->name('admin.catalogs.manage.delete-multiple');
        });

        Route::group(['prefix' => '{id}'], static function () {
            Route::get('edit', [CatalogsController::class, 'edit'])->name('admin.catalogs.manage.edit');
            Route::post('update', [CatalogsController::class, 'update'])->name('admin.catalogs.manage.update');
            Route::get('delete', [CatalogsController::class, 'delete'])->name('admin.catalogs.manage.delete');
            Route::get('show', [CatalogsController::class, 'show'])->name('admin.catalogs.manage.show');
            Route::get('/active/{active}', [CatalogsController::class, 'active'])->name('admin.catalogs.manage.changeStatus');
            Route::get('position/up', [CatalogsController::class, 'positionUp'])->name('admin.catalogs.manage.position-up');
            Route::get('position/down', [CatalogsController::class, 'positionDown'])->name('admin.catalogs.manage.position-down');
            Route::get('image/delete', [CatalogsController::class, 'deleteImage'])->name('admin.catalogs.manage.delete-image');
        });
    });
});
