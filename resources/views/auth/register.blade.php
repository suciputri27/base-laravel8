<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card">
            <div class="auth-side">
                <h2>Buat akun baru.</h2>
                <p>Mulai pakai aplikasi Anda dengan langkah yang cepat dan aman.</p>
                <ul>
                    <li><i class="fas fa-shield-halved"></i> Autentikasi aman</li>
                    <li><i class="fas fa-user-plus"></i> Registrasi mudah</li>
                    <li><i class="fas fa-gauge-high"></i> Dashboard siap pakai</li>
                </ul>
            </div>

            <div class="auth-form">
                <h3>Register</h3>
                <p class="auth-subtitle">Buat akun baru.</p>

                <div id="globalAlert" class="d-none"></div>

                <form id="registerForm" action="{{ route('register.submit') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Register</button>
                </form>

                <p class="auth-footer">Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        NProgress.configure({ showSpinner: false });
        NProgress.start();
        window.addEventListener('load', function () {
            NProgress.done();
        });

        document.getElementById('registerForm').addEventListener('submit', function (event) {
            event.preventDefault();

            App.submit(this, {
                onSuccess: function (response) {
                    App.redirect(response.redirect);
                },
                onError: function (payload) {
                    App.alert('danger', payload.message || 'Registrasi gagal.');
                }
            });
        });
    </script>
</body>
</html>
