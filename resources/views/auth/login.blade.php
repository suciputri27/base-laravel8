<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
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
                <h2>Selamat datang kembali.</h2>
                <p>Kelola aplikasi Anda dengan tampilan admin yang modern, cepat, dan responsif.</p>
                <ul>
                    <li><i class="fas fa-gauge-high"></i> Dashboard ringkas</li>
                    <li><i class="fas fa-users-gear"></i> Manajemen user dan role</li>
                    <li><i class="fas fa-bars"></i> Menu sidebar dinamis</li>
                </ul>
            </div>

            <div class="auth-form">
                <h3>Login</h3>
                <p class="auth-subtitle">Masuk untuk melanjutkan.</p>

                <div id="globalAlert" class="d-none"></div>

                <form id="loginForm" action="{{ route('login.submit') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" name="remember" value="1" id="remember">
                        <label for="remember">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Login</button>
                </form>

                <p class="auth-footer">Belum punya akun? <a href="{{ route('register') }}">Register</a></p>
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

        document.getElementById('loginForm').addEventListener('submit', function (event) {
            event.preventDefault();

            App.submit(this, {
                onSuccess: function (response) {
                    App.redirect(response.redirect);
                },
                onError: function (payload) {
                    App.alert('danger', payload.message || 'Login gagal.');
                }
            });
        });
    </script>
</body>
</html>
