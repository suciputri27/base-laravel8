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

    Route::middleware('permission:users.view')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/paginate', [UserController::class, 'paginate'])->name('users.paginate');
    });
    Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('permission:users.create');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:users.edit');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:users.delete');

    Route::middleware('permission:menus.view')->group(function () {
        Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
        Route::get('/menus/paginate', [MenuController::class, 'paginate'])->name('menus.paginate');
    });
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store')->middleware('permission:menus.create');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update')->middleware('permission:menus.edit');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy')->middleware('permission:menus.delete');

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
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/paginate', [PostController::class, 'paginate'])->name('posts.paginate');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting', [SettingController::class, 'update'])->name('setting.update');
});
