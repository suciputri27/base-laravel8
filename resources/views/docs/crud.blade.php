@extends('layouts.app')

@section('title', 'Pembuatan CRUD')
@section('page-title', 'Pembuatan CRUD')

@section('content')
    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah Membuat Modul CRUD</h5></div>
        <div class="card-body">
            <p>Contoh berikut membuat modul Produk dengan kolom nama dan harga. Seluruh langkah mengikuti pola repository dan service.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">1. Migration dan Model</h5></div>
        <div class="card-body">
            <pre><code>php artisan make:migration create_produks_table</code></pre>

            <p>Isi migration:</p>
            <pre><code>public function up()
{
    Schema::create('produks', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->integer('harga');
        $table->timestamps();
    });
}</code></pre>

            <p>Model Produk:</p>
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
        <div class="card-header"><h5 class="mb-0">2. Repository Contract dan Implementasi</h5></div>
        <div class="card-body">
            <p>Kontrak interface:</p>
            <pre><code>namespace App\Repositories\Contracts;

interface ProdukRepositoryInterface extends BaseRepositoryInterface
{
}</code></pre>

            <p>Implementasi Eloquent:</p>
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
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">3. Service</h5></div>
        <div class="card-body">
            <pre><code>namespace App\Services;

use App\Repositories\Contracts\ProdukRepositoryInterface;

class ProdukService extends BaseService
{
    public function __construct(ProdukRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function paginate(array $options = []): array
    {
        $options['search_columns'] = ['nama'];

        return parent::paginate($options);
    }
}</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">4. FormRequest</h5></div>
        <div class="card-body">
            <pre><code>namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'integer'],
        ];
    }
}</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">5. Controller</h5></div>
        <div class="card-body">
            <pre><code>namespace App\Http\Controllers;

use App\Http\Requests\ProdukRequest;
use App\Services\ProdukService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    protected $produkService;

    public function __construct(ProdukService $produkService)
    {
        $this->produkService = $produkService;
    }

    public function index()
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
        <div class="card-header"><h5 class="mb-0">6. Route dan Binding</h5></div>
        <div class="card-body">
            <p>Route pada routes/web.php:</p>
            <pre><code>Route::middleware('auth')->group(function () {
    Route::get('/produks', [ProdukController::class, 'index'])->name('produks.index');
    Route::get('/produks/paginate', [ProdukController::class, 'paginate'])->name('produks.paginate');
    Route::post('/produks', [ProdukController::class, 'store'])->name('produks.store');
    Route::put('/produks/{produk}', [ProdukController::class, 'update'])->name('produks.update');
    Route::delete('/produks/{produk}', [ProdukController::class, 'destroy'])->name('produks.destroy');
});</code></pre>

            <p>Binding repository pada AppServiceProvider:</p>
            <pre><code>$this->app->bind(ProdukRepositoryInterface::class, ProdukRepository::class);</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">7. Blade dengan Infinite Scroll</h5></div>
        <div class="card-body">
            <p>Contoh view produks/index.blade.php:</p>

@verbatim
            <pre><code>@extends('layouts.app')

@section('title', 'Produk')
@section('page-title', 'Produk')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Data Produk</h5>
            <div class="d-flex align-items-center">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari produk..." style="width: 240px; margin-right: 8px;">
                <button type="button" class="btn btn-primary btn-sm" onclick="openProdukModal()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-scroll" id="produkScroll">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="produkTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="produkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="produkForm" action="{{ route('produks.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="produkMethod" value="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="produkModalTitle">Tambah Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" id="produkNama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga</label>
                            <input type="number" name="harga" id="produkHarga" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function produkRow(item) {
            return '<tr>' +
                '<td>' + App.escapeHtml(item.nama) + '</td>' +
                '<td>' + App.escapeHtml(item.harga) + '</td>' +
                '<td>' +
                    '<button type="button" class="btn btn-secondary btn-sm" onclick="editProduk(\'' + item.encrypted_id + '\', this)" data-nama="' + App.escapeHtml(item.nama) + '" data-harga="' + App.escapeHtml(item.harga) + '"><i class="fas fa-edit"></i></button> ' +
                    '<button type="button" class="btn btn-danger btn-sm" onclick="deleteProduk(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
            '</tr>';
        }

        function openProdukModal() {
            document.getElementById('produkMethod').value = 'POST';
            document.getElementById('produkForm').action = '{{ route('produks.store') }}';
            document.getElementById('produkModalTitle').textContent = 'Tambah Produk';
            document.getElementById('produkNama').value = '';
            document.getElementById('produkHarga').value = '';
            new bootstrap.Modal(document.getElementById('produkModal')).show();
        }

        function editProduk(encryptedId, button) {
            document.getElementById('produkMethod').value = 'PUT';
            document.getElementById('produkForm').action = '{{ url('produks') }}/' + encryptedId;
            document.getElementById('produkModalTitle').textContent = 'Edit Produk';
            document.getElementById('produkNama').value = button.getAttribute('data-nama');
            document.getElementById('produkHarga').value = button.getAttribute('data-harga');
            new bootstrap.Modal(document.getElementById('produkModal')).show();
        }

        function deleteProduk(encryptedId) {
            if (!confirm('Yakin ingin menghapus produk ini?')) {
                return;
            }

            App.submitData({
                url: '{{ url('produks') }}/' + encryptedId,
                method: 'DELETE',
                onSuccess: function (response) {
                    App.alert('success', response.message);
                    table.reset();
                }
            });
        }

        document.getElementById('searchInput').addEventListener('input', function () {
            table.search(this.value);
        });

        document.getElementById('produkForm').addEventListener('submit', function (event) {
            event.preventDefault();

            App.submit(this, {
                onSuccess: function (response) {
                    bootstrap.Modal.getInstance(document.getElementById('produkModal')).hide();
                    App.alert('success', response.message);
                    table.reset();
                },
                onError: function (payload) {
                    App.alert('danger', payload.message || 'Terjadi kesalahan.');
                }
            });
        });

        var table = App.infiniteScroll({
            container: '#produkScroll',
            body: '#produkTableBody',
            endpoint: '{{ route('produks.paginate') }}',
            pageSize: 10,
            rowRenderer: produkRow
        });

        table.load(true);
    </script>
@endpush</code></pre>
@endverbatim

            <p>Penjelasan setiap helper tersedia pada menu Infinite Scroll AJAX dan Helper dan Library.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">8. Jalankan Migration</h5></div>
        <div class="card-body">
            <pre><code>php artisan migrate</code></pre>
        </div>
    </div>
@endsection
