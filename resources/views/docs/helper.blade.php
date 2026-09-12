@extends('layouts.app')

@section('title', 'Helper dan Library')
@section('page-title', 'Helper dan Library')

@section('content')
    <div class="card">
        <div class="card-header"><h5 class="mb-0">Cursor Pagination</h5></div>
        <div class="card-body">
            <p>Helper berada di App\Helpers\CursorPaginationHelper.php dan dipakai melalui BaseRepository.</p>

            <p>Parameter yang didukung:</p>
            <ul>
                <li>per_page, jumlah data per load</li>
                <li>cursor_column, kolom cursor</li>
                <li>direction, asc atau desc</li>
                <li>cursor, nilai cursor terenkripsi</li>
                <li>search, kata kunci pencarian</li>
                <li>search_columns, daftar kolom pencarian</li>
            </ul>

            <pre><code>use App\Helpers\CursorPaginationHelper;

$result = CursorPaginationHelper::paginate(
    Produk::query(),
    [
        'per_page' => 10,
        'cursor_column' => 'id',
        'direction' => 'desc',
        'cursor' => $request->input('cursor'),
        'search' => $request->input('search'),
        'search_columns' => ['nama'],
    ]
);</code></pre>

            <p>Hasil helper berisi data, next_cursor, dan has_more_pages. next_cursor sudah terenkripsi.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Format Tanggal Indonesia</h5></div>
        <div class="card-body">
            <pre><code>indo_date('2024-08-17');
indo_datetime('2024-08-17 14:30:00');</code></pre>

            <p>Hasil:</p>
            <ul>
                <li>indo_date menjadi 17 Agustus 2024</li>
                <li>indo_datetime menjadi 17 Agustus 2024, 14:30 WIB</li>
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Enkripsi ID</h5></div>
        <div class="card-body">
            <pre><code>$encoded = id_encode(10);
$decoded = id_decode($encoded);</code></pre>

            <p>Trait EncryptableIdTrait menambahkan accessor encrypted_id pada model. Tambahkan trait dan daftarkan encrypted_id pada appends.</p>

            <pre><code>use App\Traits\EncryptableIdTrait;

class Produk extends Model
{
    use EncryptableIdTrait;

    protected $hidden = ['id'];

    protected $appends = ['encrypted_id'];
}</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Base Repository dan Base Service</h5></div>
        <div class="card-body">
            <p>BaseRepository menyediakan find, findOrFail, create, update, delete, getPaginated, dan newQuery.</p>
            <p>BaseService menyediakan find, create, update, delete, dan paginate. Service baru cukup meneruskan repository ke constructor parent.</p>
        </div>
    </div>
@endsection
