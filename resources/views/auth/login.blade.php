<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SmarSchool</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Override specific for login form to fix icon positioning */
        .login-field {
            margin-bottom: 1.25rem;
        }

        .login-input-container {
            position: relative;
            width: 100%;
            margin-top: 0.5rem;
        }

        .login-input-container .material-symbols-outlined {
            position: absolute;
            left: 0.75rem;
            top: 0.875rem;
            font-size: 1.25rem;
            color: #94a3b8;
            z-index: 10;
            pointer-events: none;
            line-height: 1;
        }

        .login-input {
            width: 100%;
            padding: 0.625rem 0.75rem 0.625rem 2.5rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background-color: white;
            font-size: 0.875rem;
            transition: all 0.2s;
            height: 46px;
        }

        .login-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
        }

        .login-input.error {
            border-color: #dc2626;
        }

        .login-input.error:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.1);
        }

        .error-message {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: #dc2626;
            display: block;
        }

        /* Ensure icon stays in place regardless of error */
        .login-field .login-input-container {
            position: relative;
        }

        /* Fix for remember me checkbox */
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #475569;
            cursor: pointer;
        }

        .checkbox-label input {
            width: 1rem;
            height: 1rem;
            border-radius: 0.25rem;
            border-color: #cbd5e1;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/30">
    <div class="grid min-h-screen lg:grid-cols-[1fr_0.9fr]">
        <!-- Left Section -->
        <section
            class="hidden bg-gradient-to-br from-indigo-900 via-indigo-800 to-indigo-900 p-8 lg:flex lg:flex-col lg:justify-between relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-600/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm text-white">
                        <span class="material-symbols-outlined text-2xl">school</span>
                    </div>
                    <div>
                        <p class="headline-text text-xl font-extrabold text-white">SmarSchool</p>
                        <p class="text-sm text-indigo-200">Sistem Absensi Digital Terintegrasi</p>
                    </div>
                </div>
            </div>

            <div class="relative z-10 max-w-lg space-y-6">
                <div class="space-y-4">
                    <h1 class="headline-text text-4xl font-black leading-tight text-white">
                        Absensi Digital
                        <span class="text-indigo-300">Modern & Efisien</span>
                    </h1>
                    <p class="text-base leading-relaxed text-indigo-100">
                        Kelola kehadiran siswa per mata pelajaran dengan mudah, akurat, dan real-time.
                        Sistem absensi berbasis jadwal yang dirancang untuk guru dan admin sekolah.
                    </p>
                </div>

                <!-- Simplified feature list -->
                <div class="space-y-3 pt-4">
                    <div class="flex items-center gap-3 text-indigo-100">
                        <span class="material-symbols-outlined text-indigo-300 text-sm">check_circle</span>
                        <span class="text-sm">Absensi per sesi mata pelajaran</span>
                    </div>
                    <div class="flex items-center gap-3 text-indigo-100">
                        <span class="material-symbols-outlined text-indigo-300 text-sm">check_circle</span>
                        <span class="text-sm">Scan QR code kehadiran siswa</span>
                    </div>
                    <div class="flex items-center gap-3 text-indigo-100">
                        <span class="material-symbols-outlined text-indigo-300 text-sm">check_circle</span>
                        <span class="text-sm">Rekap absensi real-time</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="relative z-10 text-sm text-indigo-200">
                © 2026 SmarSchool. All rights reserved.
            </div>
        </section>

        <!-- Right Section - Clean Login Form -->
        <section class="flex items-center justify-center p-6 md:p-10 bg-white/50 backdrop-blur-sm">
            <div class="w-full max-w-md">
                <!-- Logo for mobile -->
                <div class="mb-8 text-center lg:hidden">
                    <div
                        class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-primary text-white mb-3">
                        <span class="material-symbols-outlined text-3xl">school</span>
                    </div>
                    <h2 class="headline-text text-2xl font-black text-on-surface">SmarSchool</h2>
                </div>

                <!-- Login Card -->
                <div class="rounded-2xl bg-white p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
                    <div class="mb-8 space-y-2">
                        <h2 class="headline-text text-2xl font-black text-on-surface">Selamat Datang Kembali</h2>
                        <p class="text-sm text-on-surface-variant">Masuk dengan akun Anda untuk melanjutkan</p>
                    </div>

                    <form action="{{ route('login') }}" method="post" data-enhanced-form>
                        @csrf

                        <!-- Email Field -->
                        <div class="login-field">
                            <label class="form-label" for="email">Alamat Email</label>
                            <div class="login-input-container">
                                <span class="material-symbols-outlined">mail</span>
                                <input type="email" name="email" id="email"
                                    class="login-input @error('email') error @enderror" value="{{ old('email') }}"
                                    placeholder="guru@sekolah.sch.id" required autofocus data-label="Email">
                            </div>
                            @error('email')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="login-field">
                            <label class="form-label" for="password">Kata Sandi</label>
                            <div class="login-input-container">
                                <span class="material-symbols-outlined">lock</span>
                                <input type="password" name="password" id="password"
                                    class="login-input @error('password') error @enderror"
                                    placeholder="Masukkan kata sandi" required data-label="Password">
                            </div>
                            @error('password')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label class="checkbox-label">
                                <input type="checkbox" name="remember">
                                <span>Ingat saya</span>
                            </label>

                            {{-- @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-sm text-primary hover:text-indigo-700 font-medium">
                                    Lupa password?
                                </a>
                            @endif --}}
                        </div>

                        <!-- Submit Button -->
                        <button class="btn-primary w-full py-3 text-base font-bold mt-6" type="submit">
                            <span class="material-symbols-outlined text-base mr-2">login</span>
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</body>

</html>
