@extends('layouts.app')

@section('title', 'Soft Delete')
@section('page-title', 'Soft Delete (Hapus Sementara)')

@section('content')
    <div class="card">
        <div class="card-header"><h5 class="mb-0">Konsep Soft Delete</h5></div>
        <div class="card-body">
            <p>Soft delete menandai data sebagai terhapus tanpa benar-benar menghapus barisnya dari database. Data tetap tersimpan di tabel, namun otomatis disembunyikan dari semua query normal seperti all, get, find, dan paginate.</p>
            <p>Keuntungan memakai soft delete:</p>
            <ul>
                <li>Data yang terhapus masih bisa dikembalikan.</li>
                <li>Riwayat data tetap terjaga untuk keperluan audit.</li>
                <li>Penghapusan tidak memicu cascade pada relasi secara langsung.</li>
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 1. Tambah Kolom deleted_at di Migration</h5></div>
        <div class="card-body">
            <p>Tambahkan method softDeletes pada tabel. Method ini otomatis membuat kolom deleted_at bertipe timestamp nullable.</p>

            <pre><code>Schema::create('produks', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->integer('harga');
    $table->timestamps();
    $table->softDeletes();
});</code></pre>

            <p>Jika tabel sudah ada, buat migration baru untuk menambah kolom.</p>

            <pre><code>php artisan make:migration add_deleted_at_to_produks_table</code></pre>

            <pre><code>Schema::table('produks', function (Blueprint $table) {
    $table->softDeletes();
});</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 2. Aktifkan Trait SoftDeletes di Model</h5></div>
        <div class="card-body">
            <p>Tambahkan trait SoftDeletes pada model. Model tetap memakai EncryptableIdTrait seperti pola module lain.</p>

            <pre><code>namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use EncryptableIdTrait;
    use SoftDeletes;

    protected $fillable = ['nama', 'harga'];

    protected $hidden = ['id'];

    protected $appends = ['encrypted_id'];
}</code></pre>

            <p>Setelah trait aktif, method delete dan destroy pada Eloquent otomatis melakukan soft delete, bukan hapus permanen.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 3. Repository untuk Restore dan Force Delete</h5></div>
        <div class="card-body">
            <p>Method delete di BaseRepository memakai destroy. Saat model memakai SoftDeletes, delete otomatis menjadi soft delete, jadi tidak perlu diubah.</p>

            <p>Untuk restore dan hapus permanen, tambahkan method baru pada interface repository.</p>

            <pre><code>namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ProdukRepositoryInterface extends BaseRepositoryInterface
{
    public function getTrashed(): Collection;

    public function restore(int $id): bool;

    public function forceDelete(int $id): bool;
}</code></pre>

            <p>Lalu implementasikan di class Eloquent repository.</p>

            <pre><code>namespace App\Repositories\Eloquent;

use App\Models\Produk;
use App\Repositories\Contracts\ProdukRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProdukRepository extends BaseRepository implements ProdukRepositoryInterface
{
    protected function model(): string
    {
        return Produk::class;
    }

    public function getTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    public function restore(int $id): bool
    {
        return (bool) $this->model->withTrashed()->findOrFail($id)->restore();
    }

    public function forceDelete(int $id): bool
    {
        return (bool) $this->model->withTrashed()->findOrFail($id)->forceDelete();
    }
}</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 4. Service</h5></div>
        <div class="card-body">
            <p>Tambahkan method restore, forceDelete, dan getTrashed pada service. Method delete bawaan BaseService sudah melakukan soft delete.</p>

            <pre><code>namespace App\Services;

use App\Repositories\Contracts\ProdukRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProdukService extends BaseService
{
    public function __construct(ProdukRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function getTrashed(): Collection
    {
        return $this->repository->getTrashed();
    }

    public function restore(int $id): bool
    {
        return $this->repository->restore($id);
    }

    public function forceDelete(int $id): bool
    {
        return $this->repository->forceDelete($id);
    }
}</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 5. Controller</h5></div>
        <div class="card-body">
            <p>Tambahkan method restore dan forceDelete. ID yang dikirim dari form memakai encrypted id, jadi decode terlebih dahulu dengan id_decode.</p>

            <pre><code>public function destroy(string $produk): JsonResponse
{
    $this->produkService->delete(id_decode($produk));

    return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
}

public function restore(string $produk): JsonResponse
{
    $this->produkService->restore(id_decode($produk));

    return response()->json(['success' => true, 'message' => 'Data berhasil dikembalikan.']);
}

public function forceDelete(string $produk): JsonResponse
{
    $this->produkService->forceDelete(id_decode($produk));

    return response()->json(['success' => true, 'message' => 'Data berhasil dihapus permanen.']);
}</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 6. Route</h5></div>
        <div class="card-body">
            <p>Tambahkan route untuk restore dan hapus permanen di routes/web.php.</p>

            <pre><code>Route::delete('/produks/{produk}', [ProdukController::class, 'destroy'])->name('produks.destroy');
Route::post('/produks/{produk}/restore', [ProdukController::class, 'restore'])->name('produks.restore');
Route::delete('/produks/{produk}/force-delete', [ProdukController::class, 'forceDelete'])->name('produks.force-delete');</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 7. Tampilan Daftar Data Terhapus</h5></div>
        <div class="card-body">
            <p>Untuk menampilkan data yang sudah dihapus, panggil getTrashed lalu kirim ke view. Tambahkan tombol Restore dan Hapus Permanen pada setiap baris.</p>

            <pre><code>public function trashed()
{
    $produks = $this->produkService->getTrashed();

    return view('produks.trashed', compact('produks'));
}</code></pre>

            <p>Contoh tombol aksi pada tabel.</p>

@verbatim
            <pre><code>&lt;button class="btn btn-sm btn-success" onclick="restoreProduk('{{ $produk->encrypted_id }}')"&gt;Restore&lt;/button&gt;
&lt;button class="btn btn-sm btn-danger" onclick="forceDeleteProduk('{{ $produk->encrypted_id }}')"&gt;Hapus Permanen&lt;/button&gt;</code></pre>
@endverbatim

            <p>Route untuk halaman daftar terhapus.</p>

            <pre><code>Route::get('/produks/trashed', [ProdukController::class, 'trashed'])->name('produks.trashed');</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Catatan Penting</h5></div>
        <div class="card-body">
            <ul>
                <li>Query normal otomatis melewati data terhapus. Gunakan withTrashed untuk mengambil semua data, dan onlyTrashed untuk mengambil data terhapus saja.</li>
                <li>Kolom dengan unique constraint tetap aktif pada data yang di-soft delete. Saat validasi unique, tambahkan whereNull deleted_at agar data terhapus tidak dianggap duplikat.</li>
                <li>Relasi hasMany dan belongsTo tetap berfungsi pada data terhapus. Jika ingin menampilkan relasi milik data terhapus, gunakan withTrashed pada model terkait.</li>
                <li>Metode restore hanya mengosongkan deleted_at. Metode forceDelete benar-benar menghapus baris dari database dan tidak bisa dikembalikan.</li>
                <li>Bila perlu menghapus banyak data sekaligus secara permanen, gunakan onlyTrashed lalu forceDelete pada query builder.</li>
            </ul>

            <p>Contoh validasi unique dengan soft delete.</p>

            <pre><code>use Illuminate\Validation\Rule;

$rules['nama'] = [
    'required',
    'string',
    'max:255',
    Rule::unique('produks', 'nama')->whereNull('deleted_at'),
];

if (! $this->isMethod('post')) {
    $id = $this->route('produk') ? id_decode((string) $this->route('produk')) : null;
    $rules['nama'][] = $id ? 'ignore:' . $id : '';
}</code></pre>
        </div>
    </div>
@endsection
