@extends('layouts.app')

@section('title', 'Panduan Module Baru')
@section('page-title', 'Panduan Module Baru')

@section('content')
    <div class="card">
        <div class="card-header"><h5 class="mb-0">Alur Pembuatan Module Baru</h5></div>
        <div class="card-body">
            <p>Setiap module baru mengikuti pola yang sama. Urutan pengerjaan yang disarankan:</p>
            <ol>
                <li>Migration dan Model</li>
                <li>Repository Contract dan Implementasi Eloquent</li>
                <li>Service dan custom business logic</li>
                <li>FormRequest untuk validasi</li>
                <li>Controller</li>
                <li>Route dan binding ServiceProvider</li>
                <li>View Blade</li>
                <li>Menu sidebar</li>
                <li>Migrasi dan seeder</li>
            </ol>
            <p>Alur request berjalan dari Route, Controller, Service, Repository, lalu kembali menjadi JSON atau view. Controller tidak boleh berisi business logic.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 1. Migration dan Model</h5></div>
        <div class="card-body">
            <p>Contoh module Produk dengan tabel produks.</p>

            <pre><code>php artisan make:migration create_produks_table</code></pre>

            <pre><code>Schema::create('produks', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->integer('harga');
    $table->timestamps();
});</code></pre>

            <p>Model Produk memakai trait EncryptableIdTrait, menyembunyikan id asli, dan menambahkan encrypted_id.</p>

            <pre><code>namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use EncryptableIdTrait;

    protected $fillable = ['nama', 'harga'];

    protected $hidden = ['id'];

    protected $appends = ['encrypted_id'];
}</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 2. Repository Contract dan Implementasi</h5></div>
        <div class="card-body">
            <p>Buat interface kontrak terlebih dahulu.</p>

            <pre><code>namespace App\Repositories\Contracts;

interface ProdukRepositoryInterface extends BaseRepositoryInterface
{
}</code></pre>

            <p>Lalu implementasi Eloquent.</p>

            <pre><code>namespace App\Repositories\Eloquent;

use App\Models\Produk;
use App\Repositories\Contracts\ProdukRepositoryInterface;

class ProdukRepository extends BaseRepository implements ProdukRepositoryInterface
{
    protected function model(): string
    {
        return Produk::class;
    }
}</code></pre>

            <p>Repository hanya menangani kueri database. Jika perlu eager load relasi untuk pagination, override getPaginated.</p>

            <pre><code>use App\Helpers\CursorPaginationHelper;

public function getPaginated(array $options = []): array
{
    return CursorPaginationHelper::paginate($this->model->with('kategori'), $options);
}</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Kenapa Interface Repository Bisa Kosong</h5></div>
        <div class="card-body">
            <p>Interface repository boleh kosong karena BaseRepositoryInterface sudah menyediakan operasi standar:</p>
            <ul>
                <li>find dan findOrFail</li>
                <li>create, update, dan delete</li>
                <li>getPaginated untuk cursor pagination</li>
                <li>newQuery untuk mengambil query builder</li>
            </ul>

            <p>Untuk module sederhana tanpa query khusus, interface cukup meng-extend BaseRepositoryInterface tanpa method tambahan.</p>

            <p>Interface perlu diisi saat module membutuhkan query spesifik yang tidak ada di base. Isinya berupa deklarasi method beserta tipe kembalian.</p>

            <pre><code>namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProdukRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug(string $slug): ?Model;

    public function getAktif(): Collection;

    public function findByKategori(int $kategoriId): Collection;
}</code></pre>

            <p>Implementasinya diletakkan di class Eloquent repository.</p>

            <pre><code>public function findBySlug(string $slug): ?Model
{
    return $this->model->where('slug', $slug)->first();
}

public function getAktif(): Collection
{
    return $this->model->where('is_active', true)->orderBy('nama')->get();
}

public function findByKategori(int $kategoriId): Collection
{
    return $this->model->where('kategori_id', $kategoriId)->get();
}</code></pre>

            <p>Kasus umum yang membuat interface berisi method:</p>
            <ul>
                <li>Mencari data berdasarkan field unik selain id, contoh findBySlug atau findByEmail.</li>
                <li>Mengambil data dengan kondisi tertentu, contoh getAktif atau getDraft.</li>
                <li>Mengambil data berdasarkan relasi, contoh findByKategori.</li>
                <li>Membutuhkan eager load khusus sebelum pagination.</li>
                <li>Membutuhkan query aggregasi seperti total atau rata rata.</li>
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 3. Service dan Custom Logic</h5></div>
        <div class="card-body">
            <p>Service menampung seluruh business logic. BaseService sudah menyediakan find, create, update, delete, dan paginate.</p>

            <pre><code>namespace App\Services;

use App\Repositories\Contracts\ProdukRepositoryInterface;
use Illuminate\Support\Str;

class ProdukService extends BaseService
{
    public function __construct(ProdukRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data)
    {
        $data['slug'] = Str::slug($data['nama']);

        return $this->repository->create($data);
    }

    public function paginate(array $options = []): array
    {
        $options['search_columns'] = ['nama'];

        $result = parent::paginate($options);

        $result['data'] = $result['data']->map(function ($produk) {
            return [
                'encrypted_id' => id_encode((int) $produk->id),
                'nama' => $produk->nama,
                'harga' => $produk->harga,
            ];
        })->values();

        return $result;
    }
}</code></pre>

            <p>Custom logic selalu diletakkan di Service. Contoh custom logic yang umum:</p>
            <ul>
                <li>Generate slug dari judul atau nama.</li>
                <li>Upload file dan hapus file lama.</li>
                <li>Menghitung total, potongan, atau nilai turunan.</li>
                <li>Transaksi database dengan DB::transaction.</li>
                <li>Menggabungkan data dari beberapa repository.</li>
            </ul>

            <p>Jika service membutuhkan service lain, inject lewat constructor.</p>

            <pre><code>public function __construct(ProdukRepositoryInterface $repository, KategoriService $kategoriService)
{
    parent::__construct($repository);
    $this->kategoriService = $kategoriService;
}</code></pre>

            <p>Contoh transaksi database di service:</p>

            <pre><code>use Illuminate\Support\Facades\DB;

public function createWithStock(array $data)
{
    return DB::transaction(function () use ($data) {
        $produk = $this->repository->create($data);
        $produk->stok()->create(['jumlah' => $data['stok_awal']]);

        return $produk;
    });
}</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 4. FormRequest</h5></div>
        <div class="card-body">
            <p>FormRequest memvalidasi input dan mendekode ID terenkripsi bila perlu.</p>

            <pre><code>namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProdukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nama' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'integer'],
        ];

        if ($this->isMethod('post')) {
            $rules['nama'][] = 'unique:produks,nama';
        } else {
            $id = $this->route('produk') ? id_decode((string) $this->route('produk')) : null;
            $rules['nama'][] = Rule::unique('produks', 'nama')->ignore($id);
        }

        return $rules;
    }
}</code></pre>

            <p>Jika form mengirim foreign key terenkripsi, decode di prepareForValidation.</p>

            <pre><code>protected function prepareForValidation()
{
    if ($this->has('kategori_id') && $this->filled('kategori_id')) {
        $this->merge(['kategori_id' => id_decode((string) $this->input('kategori_id'))]);
    }
}</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 5. Controller</h5></div>
        <div class="card-body">
            <p>Controller hanya menerima request, memanggil service, lalu mengembalikan JSON atau view.</p>

            <pre><code>namespace App\Http\Controllers;

use App\Http\Requests\ProdukRequest;
use App\Services\ProdukService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProdukController extends Controller
{
    protected $produkService;

    public function __construct(ProdukService $produkService)
    {
        $this->produkService = $produkService;
    }

    public function index(): View
    {
        return view('produks.index');
    }

    public function paginate(Request $request): JsonResponse
    {
        $result = $this->produkService->paginate([
            'per_page' => (int) $request->input('per_page', 10),
            'cursor' => $request->input('cursor'),
            'search' => $request->input('search'),
        ]);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'next_cursor' => $result['next_cursor'],
            'has_more_pages' => $result['has_more_pages'],
        ]);
    }

    public function store(ProdukRequest $request): JsonResponse
    {
        $this->produkService->create($request->validated());

        return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan.']);
    }

    public function update(ProdukRequest $request, string $produk): JsonResponse
    {
        $this->produkService->update(id_decode($produk), $request->validated());

        return response()->json(['success' => true, 'message' => 'Produk berhasil diperbarui.']);
    }

    public function destroy(string $produk): JsonResponse
    {
        $this->produkService->delete(id_decode($produk));

        return response()->json(['success' => true, 'message' => 'Produk berhasil dihapus.']);
    }
}</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 6. Route dan Binding</h5></div>
        <div class="card-body">
            <p>Daftarkan route di routes/web.php di dalam grup auth.</p>

            <pre><code>Route::get('/produks', [ProdukController::class, 'index'])->name('produks.index');
Route::get('/produks/paginate', [ProdukController::class, 'paginate'])->name('produks.paginate');
Route::post('/produks', [ProdukController::class, 'store'])->name('produks.store');
Route::put('/produks/{produk}', [ProdukController::class, 'update'])->name('produks.update');
Route::delete('/produks/{produk}', [ProdukController::class, 'destroy'])->name('produks.destroy');</code></pre>

            <p>Daftarkan binding repository di AppServiceProvider.</p>

            <pre><code>$this->app->bind(ProdukRepositoryInterface::class, ProdukRepository::class);</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 7. View Blade</h5></div>
        <div class="card-body">
            <p>Ada dua pola tampilan yang bisa dipilih.</p>

            <ul>
                <li>Modal: cocok untuk form sederhana. Lihat halaman Kategori.</li>
                <li>Full page: cocok untuk form kompleks dengan upload atau banyak field. Lihat halaman Tambah Berita.</li>
            </ul>

            <p>Tabel data memakai App.infiniteScroll untuk auto load saat scroll bawah.</p>

@verbatim
            <pre><code>var table = App.infiniteScroll({
    container: '#produkScroll',
    body: '#produkTableBody',
    endpoint: '{{ route('produks.paginate') }}',
    pageSize: 10,
    rowRenderer: produkRow
});

table.load(true);</code></pre>
@endverbatim
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 8. Menu Sidebar</h5></div>
        <div class="card-body">
            <p>Menu bisa ditambahkan lewat halaman Menu Management atau lewat seeder.</p>

            <pre><code>Menu::firstOrCreate(
    ['route_or_url' => 'produks.index', 'parent_id' => $parent->id],
    [
        'name' => 'Produk',
        'icon' => 'fas fa-box',
        'permission_name' => null,
        'order_no' => 1,
        'is_active' => true,
    ]
);</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 9. Migrasi dan Seeder</h5></div>
        <div class="card-body">
            <pre><code>php artisan migrate
php artisan db:seed --class=NamaSeeder</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Panduan Kasus Khusus</h5></div>
        <div class="card-body">
            <ul>
                <li>Relasi antar module: buat foreign key, relasi belongsTo dan hasMany, lalu eager load di repository.</li>
                <li>Upload file: gunakan App\Helpers\FileUploadHelper untuk validasi mime, size, dan nama acak.</li>
                <li>Enkripsi ID di form: decode di prepareForValidation, atau id_decode di controller.</li>
                <li>Pencarian: isi search_columns pada method paginate di service.</li>
                <li>Validasi unik saat update: gunakan Rule::unique dengan ignore id.</li>
                <li>Soft delete: tambah use SoftDeletes di model dan kolom deleted_at.</li>
                <li>Format tanggal: gunakan indo_date atau indo_datetime.</li>
                <li>Select2: pakai class select2 pada select lalu inisialisasi dengan jQuery.</li>
                <li>Upload gambar cantik: gunakan file-dropzone dengan preview.</li>
                <li>RBAC: tambah permission dan middleware permission pada route.</li>
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Checklist Akhir</h5></div>
        <div class="card-body">
            <ol>
                <li>Model memakai EncryptableIdTrait, hidden id, dan appends encrypted_id.</li>
                <li>Repository interface terdaftar di AppServiceProvider.</li>
                <li>Business logic ada di Service, bukan Controller.</li>
                <li>Controller hanya memanggil service dan mengembalikan response.</li>
                <li>Semua route memakai middleware auth dan permission bila perlu.</li>
                <li>Form memakai AJAX dan tombol memiliki loading state.</li>
                <li>Tabel memakai infinite scroll dengan empty, loading, dan error state.</li>
                <li>Menu sidebar sudah ditambahkan.</li>
            </ol>
        </div>
    </div>
@endsection
