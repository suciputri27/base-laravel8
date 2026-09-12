@extends('layouts.app')

@section('title', 'RBAC dan Permission')
@section('page-title', 'RBAC dan Permission')

@section('content')
    <div class="card">
        <div class="card-header"><h5 class="mb-0">Permission Dasar</h5></div>
        <div class="card-body">
            <ul>
                <li>dashboard.view</li>
                <li>users.view, users.create, users.edit, users.delete</li>
                <li>roles.view, roles.create, roles.edit, roles.delete</li>
                <li>permissions.view, permissions.create, permissions.edit, permissions.delete</li>
                <li>menus.view, menus.create, menus.edit, menus.delete</li>
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Super Admin Bypass</h5></div>
        <div class="card-body">
            <p>Role Super Admin melewati semua permission melalui Gate::before di App\Providers\AuthServiceProvider.</p>

            <pre><code>Gate::before(function ($user) {
    if ($user->hasRole('Super Admin')) {
        return true;
    }

    return null;
});</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Melindungi Route</h5></div>
        <div class="card-body">
            <p>Gunakan middleware permission atau role.</p>

            <pre><code>Route::get('/users', [UserController::class, 'index'])
    ->middleware('permission:users.view');

Route::get('/admin', [AdminController::class, 'index'])
    ->middleware('role:Super Admin');</code></pre>

            <p>Jika user tidak memiliki izin, aplikasi mengembalikan response JSON 403 yang ditangani App\Exceptions\Handler.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Memberi Permission pada Menu</h5></div>
        <div class="card-body">
            <p>Isi kolom permission_name pada menu dengan nama permission. Menu hanya tampil bagi user yang memiliki permission tersebut. Kosongkan permission_name agar menu tampil untuk semua user.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Manajemen Role dan Permission</h5></div>
        <div class="card-body">
            <p>Role dan permission dikelola lewat halaman Role Management dan Permission Management. Role dapat diberi banyak permission sekaligus lewat form multi select.</p>
        </div>
    </div>
@endsection
