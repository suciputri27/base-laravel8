<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StrukturOrganisasiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::view('/docs/arsitektur', 'docs.arsitektur')->name('docs.arsitektur');
    Route::view('/docs/crud', 'docs.crud')->name('docs.crud');
    Route::view('/docs/menu', 'docs.menu')->name('docs.menu');
    Route::view('/docs/helper', 'docs.helper')->name('docs.helper');
    Route::view('/docs/ajax', 'docs.ajax')->name('docs.ajax');
    Route::view('/docs/rbac', 'docs.rbac')->name('docs.rbac');
    Route::view('/docs/modul-kategori-berita', 'docs.modul-kategori-berita')->name('docs.modul-kategori-berita');
    Route::view('/docs/panduan-module', 'docs.panduan-module')->name('docs.panduan-module');
    Route::view('/docs/softdelete', 'docs.softdelete')->name('docs.softdelete');

    Route::middleware('permission:users.view')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/paginate', [UserController::class, 'paginate'])->name('users.paginate');
        Route::get('/users/trashed', [UserController::class, 'trashed'])->name('users.trashed')->middleware('role:Super Admin');
    });
    Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('permission:users.create');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:users.edit');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:users.delete');
    Route::post('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore')->middleware(['permission:users.edit', 'role:Super Admin']);
    Route::delete('/users/{user}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete')->middleware(['permission:users.delete', 'role:Super Admin']);

    Route::middleware('permission:menus.view')->group(function () {
        Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
        Route::get('/menus/paginate', [MenuController::class, 'paginate'])->name('menus.paginate');
        Route::get('/menus/trashed', [MenuController::class, 'trashed'])->name('menus.trashed')->middleware('role:Super Admin');
    });
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store')->middleware('permission:menus.create');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update')->middleware('permission:menus.edit');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy')->middleware('permission:menus.delete');
    Route::post('/menus/{menu}/restore', [MenuController::class, 'restore'])->name('menus.restore')->middleware(['permission:menus.edit', 'role:Super Admin']);
    Route::delete('/menus/{menu}/force-delete', [MenuController::class, 'forceDelete'])->name('menus.force-delete')->middleware(['permission:menus.delete', 'role:Super Admin']);

    Route::middleware('permission:roles.view')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/paginate', [RoleController::class, 'paginate'])->name('roles.paginate');
    });
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:roles.create');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:roles.edit');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:roles.delete');

    Route::middleware('permission:permissions.view')->group(function () {
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/paginate', [PermissionController::class, 'paginate'])->name('permissions.paginate');
    });
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store')->middleware('permission:permissions.create');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:permissions.edit');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('permission:permissions.delete');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/paginate', [CategoryController::class, 'paginate'])->name('categories.paginate');
    Route::get('/categories/trashed', [CategoryController::class, 'trashed'])->name('categories.trashed')->middleware('role:Super Admin');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore')->middleware('role:Super Admin');
    Route::delete('/categories/{category}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete')->middleware('role:Super Admin');

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/paginate', [PostController::class, 'paginate'])->name('posts.paginate');
    Route::get('/posts/trashed', [PostController::class, 'trashed'])->name('posts.trashed')->middleware('role:Super Admin');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/restore', [PostController::class, 'restore'])->name('posts.restore')->middleware('role:Super Admin');
    Route::delete('/posts/{post}/force-delete', [PostController::class, 'forceDelete'])->name('posts.force-delete')->middleware('role:Super Admin');

    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting', [SettingController::class, 'update'])->name('setting.update');

    Route::get('/struktur', [StrukturOrganisasiController::class, 'index'])->name('struktur_organisasi.index');
    Route::get('/struktur/paginate', [StrukturOrganisasiController::class, 'paginate'])->name('struktur_organisasi.paginate');
    Route::get('/struktur/create', [StrukturOrganisasiController::class, 'create'])->name('struktur_organisasi.create');
    Route::post('/struktur', [StrukturOrganisasiController::class, 'store'])->name('struktur_organisasi.store');
    Route::get('/struktur/{struktur}/edit', [StrukturOrganisasiController::class, 'edit'])->name('struktur_organisasi.edit');
    Route::put('/struktur/{struktur}', [StrukturOrganisasiController::class, 'update'])->name('struktur_organisasi.update');
    Route::delete('/struktur/{struktur}', [StrukturOrganisasiController::class, 'destroy'])->name('struktur_organisasi.destroy');
});
