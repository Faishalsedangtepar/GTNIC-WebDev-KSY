<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Login - Aura Material</title>

    {{-- Font (hapus jika sudah di-import di tempat lain) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0" rel="stylesheet">

    @vite(['resources/css/login.css', 'resources/js/login.js'])
</head>
<body>
    <div class="page">

        {{-- Header merek --}}
        <header class="brand">
            <div class="brand-left">
                <div class="brand-logo">
                    <span class="material-symbols-outlined">hardware</span>
                </div>
                <div class="brand-name">Aura<span>Material</span></div>
            </div>
            <div class="status">
                <span class="status-dot"></span>
                Online
            </div>
        </header>

        {{-- Judul --}}
        <section class="intro">
            <h1>Masuk ke Sistem Aura Material</h1>
            <p>Pilih peran operasional untuk mulai transaksi toko dan logistik gudang.</p>
        </section>

        {{-- Form login --}}
        <form class="card" method="POST" action="{{ route('login') }}" id="login-form">
            @csrf

            {{-- Username --}}
            <div class="field">
                <label class="field-label" for="username">ID Karyawan / No. HP</label>
                <div class="input-wrap">
                    <span class="material-symbols-outlined input-icon">badge</span>
                    <input
                        class="input"
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        required
                        autofocus
                    >
                </div>
                @error('username')
                    <p style="color:red; margin:0; font-size:0.875rem">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password / PIN --}}
            <div class="field">
                <div class="field-row">
                    <label class="field-label" for="password">PIN Keamanan Toko / Sandi</label>
                    <button type="button" class="toggle-pin" id="toggle-pin">
                        <span class="material-symbols-outlined">visibility</span>
                        Lihat
                    </button>
                </div>
                <div class="input-wrap">
                    <span class="material-symbols-outlined input-icon">lock</span>
                    <input
                        class="input input-pin"
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        required
                    >
                    <span class="pin-badge">6-DIGIT</span>
                </div>
                @error('password')
                    <p style="color:red; margin:0; font-size:0.875rem">{{ $message }}</p>
                @enderror
            </div>

            {{-- PIN cepat & biometrik --}}
            <button type="button" class="btn btn-secondary" id="btn-quick">
                <span class="material-symbols-outlined">fingerprint</span>
                PIN Cepat &amp; Biometrik Gudang
            </button>

            {{-- Submit --}}
            <button type="submit" class="btn btn-primary">
                Masuk
                <span class="material-symbols-outlined">arrow_forward</span>
            </button>
        </form>

    </div>
    
</body>
</html>