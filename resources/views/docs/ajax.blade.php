@extends('layouts.app')

@section('title', 'Infinite Scroll AJAX')
@section('page-title', 'Infinite Scroll AJAX')

@section('content')
    <div class="card">
        <div class="card-header"><h5 class="mb-0">Struktur Tabel</h5></div>
        <div class="card-body">
            <p>Bungkus tabel di dalam div dengan class table-scroll agar bisa discroll.</p>

            <pre><code>&lt;div class="table-scroll" id="produkScroll"&gt;
    &lt;table class="table"&gt;
        &lt;thead&gt;
            &lt;tr&gt;
                &lt;th&gt;Nama&lt;/th&gt;
                &lt;th&gt;Harga&lt;/th&gt;
            &lt;/tr&gt;
        &lt;/thead&gt;
        &lt;tbody id="produkTableBody"&gt;&lt;/tbody&gt;
    &lt;/table&gt;
&lt;/div&gt;</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Script Auto Load Data</h5></div>
        <div class="card-body">
            <p>Gunakan App.infiniteScroll dengan rowRenderer untuk menggambar baris.</p>

            <pre><code>function produkRow(item) {
    return '&lt;tr&gt;' +
        '&lt;td&gt;' + App.escapeHtml(item.nama) + '&lt;/td&gt;' +
        '&lt;td&gt;' + App.escapeHtml(item.harga) + '&lt;/td&gt;' +
    '&lt;/tr&gt;';
}

var table = App.infiniteScroll({
    container: '#produkScroll',
    body: '#produkTableBody',
    endpoint: '@{{ route('produks.paginate') }}',
    pageSize: 10,
    rowRenderer: produkRow
});

table.load(true);</code></pre>

            <p>Ketika scroll mencapai batas bawah, JavaScript memuat 10 record berikutnya memakai next_cursor dari response.</p>

            <p>Fungsi pendukung:</p>
            <ul>
                <li>table.load(true) memuat dari awal</li>
                <li>table.search('kata') mencari dan memuat dari awal</li>
                <li>table.reset() mereset pencarian dan memuat dari awal</li>
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">AJAX Submit Form</h5></div>
        <div class="card-body">
            <pre><code>document.getElementById('produkForm').addEventListener('submit', function (event) {
    event.preventDefault();

    App.submit(this, {
        onSuccess: function (response) {
            App.alert('success', response.message);
            table.reset();
        },
        onError: function (payload) {
            App.alert('danger', payload.message || 'Terjadi kesalahan.');
        }
    });
});</code></pre>

            <p>Untuk delete gunakan App.submitData dengan method DELETE.</p>

            <pre><code>App.submitData({
    url: '{{ url('produks') }}/' + encryptedId,
    method: 'DELETE',
    onSuccess: function (response) {
        App.alert('success', response.message);
        table.reset();
    }
});</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Penanganan CSRF</h5></div>
        <div class="card-body">
            <p>Helper JavaScript otomatis membaca meta tag csrf-token dan mengirim header X-CSRF-TOKEN pada setiap request.</p>
        </div>
    </div>
@endsection
