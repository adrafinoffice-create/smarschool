<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart School</title>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-body-tertiary">

    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card shadow p-4" style="width:400px">

            <h3 class="text-center fw-bold mb-2">Absensi Siswa</h3>
            <p class="text-center mb-4">Silakan Login</p>

            <form action="{{ route('login') }}" method="post">
                @csrf

                <div class="mb-3">
                    <label for="email">Masukan Email</label>
                    <input type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                        placeholder="Masukan Email" required autofocus>

                    @error('email')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password">Masukan Password</label>
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Masukan Password"
                        required>

                    @error('password')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button class="btn btn-primary w-100" type="submit">
                    Login
                </button>

            </form>
        </div>
    </div>

</body>

</html>
