@extends('layouts.app')

@section('title', 'Modul Kategori dan Berita')
@section('page-title', 'Modul Kategori dan Berita')

@section('content')
    <div class="card">
        <div class="card-header"><h5 class="mb-0">Ringkasan</h5></div>
        <div class="card-body">
            <p>Halaman ini menjelaskan langkah lengkap membangun dua modul CRUD yang saling berelasi.</p>
            <ul>
                <li>Kategori sebagai master data, dibuat dengan form modal dan infinite scroll.</li>
                <li>Berita sebagai modul utama, dibuat dengan form halaman penuh, Select2, dan upload gambar.</li>
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 1. Helper Upload Aman</h5></div>
        <div class="card-body">
            <p>Buat file App\Helpers\FileUploadHelper.php. Helper memvalidasi tipe file memakai finfo, bukan hanya ekstensi dari client, dan menyimpan file dengan nama acak.</p>

            <pre><code>namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadHelper
{
    protected const ALLOWED_MIME = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    public static function upload(UploadedFile $file, string $directory = 'uploads', int $maxKb = 2048): string
    {
        if (! $file->isValid()) {
            throw new \RuntimeException('File tidak valid.');
        }

        if ($file->getSize() > $maxKb * 1024) {
            throw new \RuntimeException('Ukuran file maksimal ' . $maxKb . ' KB.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file->getPathname());

        if (! isset(self::ALLOWED_MIME[$mime])) {
            throw new \RuntimeException('Tipe file tidak diizinkan.');
        }

        $extension = self::ALLOWED_MIME[$mime];
        $filename = Str::random(40) . '.' . $extension;

        return Storage::disk('public')->putFileAs($directory, $file, $filename, 'public');
    }

    public static function delete(string $path): bool
    {
        if (! $path) {
            return false;
        }

        return Storage::disk('public')->delete($path);
    }

    public static function url(string $path): string
    {
        return Storage::disk('public')->url($path);
    }
}</code></pre>

            <p>Tambahkan fungsi global storage_url pada app/Support/helpers.php.</p>

            <pre><code>function storage_url(string $path): string
{
    return \App\Helpers\FileUploadHelper::url($path);
}</code></pre>

            <p>Jalankan symlink storage sekali saja.</p>

            <pre><code>php artisan storage:link</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 2. Master Kategori</h5></div>
        <div class="card-body">
            <p>Buat migration categories.</p>

            <pre><code>Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});</code></pre>

            <p>Buat model, repository interface, implementasi Eloquent, service, FormRequest, dan controller seperti pola yang sudah dipakai modul lain.</p>

            <p>Kunci service kategori adalah pembuatan slug otomatis.</p>

            <pre><code>public function create(array $data)
{
    $data['slug'] = Str::slug($data['name']);

    return $this->repository->create($data);
}</code></pre>

            <p>Controller memakai pola modal dan infinite scroll. View kategori memakai helper App.infiniteScroll seperti halaman User Management.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 3. Modul Berita</h5></div>
        <div class="card-body">
            <p>Buat migration posts dengan foreign key ke categories.</p>

            <pre><code>Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('category_id')->nullable();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('excerpt')->nullable();
    $table->longText('content');
    $table->string('thumbnail')->nullable();
    $table->string('status')->default('draft');
    $table->timestamp('published_at')->nullable();
    $table->timestamps();

    $table->index('category_id');
    $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
});</code></pre>

            <p>Service berita menangani upload thumbnail dan penggantian file lama.</p>

            <pre><code>public function create(array $data)
{
    if (isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
        $data['thumbnail'] = FileUploadHelper::upload($data['thumbnail'], 'posts');
    }

    $data['slug'] = Str::slug($data['title']);
    $data['published_at'] = $this->resolvePublishedAt($data);

    return $this->repository->create($data);
}

public function update(int $id, array $data)
{
    $model = $this->repository->findOrFail($id);

    if (isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
        if ($model->thumbnail) {
            FileUploadHelper::delete($model->thumbnail);
        }

        $data['thumbnail'] = FileUploadHelper::upload($data['thumbnail'], 'posts');
    } else {
        unset($data['thumbnail']);
    }

    $data['slug'] = Str::slug($data['title']);
    $data['published_at'] = $this->resolvePublishedAt($data, $model);

    return $this->repository->update($id, $data);
}</code></pre>

            <p>FormRequest berita memakai validasi image dan decode category_id terenkripsi.</p>

            <pre><code>public function rules(): array
{
    return [
        'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        'title' => ['required', 'string', 'max:255', Rule::unique('posts', 'title')->ignore($this->route('post') ? id_decode((string) $this->route('post')) : null)],
        'excerpt' => ['nullable', 'string'],
        'content' => ['required', 'string'],
        'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:2048'],
        'status' => ['required', 'in:draft,published'],
    ];
}

protected function prepareForValidation()
{
    if ($this->has('category_id') && $this->filled('category_id')) {
        $this->merge(['category_id' => id_decode((string) $this->input('category_id'))]);
    }
}</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Langkah 4. Form Full Page dengan Select2 dan Dropzone</h5></div>
        <div class="card-body">
            <p>Halaman Tambah dan Edit Berita memakai form full page, bukan modal. Select kategori memakai Select2, upload thumbnail memakai area dropzone dengan preview.</p>

@verbatim
            <pre><code>&lt;div class="col-md-6"&gt;
    &lt;label class="form-label"&gt;Kategori&lt;/label&gt;
    &lt;select name="category_id" id="category_id" class="form-control"&gt;
        &lt;option value=""&gt;Pilih Kategori&lt;/option&gt;
        &lt;option value="{{ $category->encrypted_id }}"&gt;{{ $category->name }}&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;div class="file-dropzone" id="thumbnailDropzone"&gt;
    &lt;input type="file" name="thumbnail" id="thumbnailInput" accept="image/*" hidden&gt;
    &lt;div class="file-dropzone-inner" id="thumbnailDropzoneInner"&gt;
        &lt;i class="fas fa-cloud-arrow-up"&gt;&lt;/i&gt;
        &lt;p&gt;Seret gambar ke sini atau klik untuk pilih&lt;/p&gt;
    &lt;/div&gt;
    &lt;div class="file-preview d-none" id="thumbnailPreview"&gt;
        &lt;img id="thumbnailPreviewImage" src="" alt=""&gt;
        &lt;button type="button" id="thumbnailRemove" class="btn btn-danger btn-sm"&gt;&lt;i class="fas fa-trash"&gt;&lt;/i&gt;&lt;/button&gt;
    &lt;/div&gt;
&lt;/div&gt;</code></pre>
@endverbatim

            <p>Inisialisasi Select2 dan logika dropzone memakai JavaScript yang sudah tersedia di resources/views/posts/form.blade.php.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Keamanan Upload dan Tampilan</h5></div>
        <div class="card-body">
            <ul>
                <li>Tipe file dideteksi memakai finfo, bukan ekstensi yang dikirim client.</li>
                <li>Hanya JPG, PNG, WEBP, dan GIF yang diizinkan. SVG ditolak agar terhindar dari XSS.</li>
                <li>Ukuran file dibatasi 2 MB.</li>
                <li>Nama file disimpan acak agar sulit ditebak dan mencegah path traversal.</li>
                <li>File disimpan di storage/app/public dan diakses lewat symlink public/storage.</li>
                <li>Saat menampilkan gambar gunakan helper storage_url dan selalu escape output dengan fungsi e() atau App.escapeHtml.</li>
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Menjalankan</h5></div>
        <div class="card-body">
            <pre><code>php artisan storage:link
php artisan migrate
php artisan db:seed --class=ContentSeeder</code></pre>
        </div>
    </div>
@endsection
