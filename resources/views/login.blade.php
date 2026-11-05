
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <title>Login - Aura Material</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/login.js'])

    <style>
        html, body {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            overscroll-behavior: none;
        }

        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }

        .pt-safe {
            padding-top: env(safe-area-inset-top, 0px);
        }

        ::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="bg-surface font-body-md text-on-surface pt-safe pb-safe flex flex-col min-h-screen">

<main class="flex-1 flex flex-col relative w-full bg-surface min-h-screen">

    <div class="flex flex-col w-full pb-8">

        <div class="px-4 flex flex-col pt-4">

            <!-- HEADER BRAND -->
            <header class="flex items-center justify-between py-2 border-b border-surface-container pb-3">

                <div class="flex items-center gap-2.5">

                    <div class="w-10 h-10 rounded-lg bg-slate-900 flex items-center justify-center shadow-sm border border-slate-700">
                        <span class="material-symbols-outlined text-secondary-container text-[22px]">
                            hardware
                        </span>
                    </div>

                    <div class="flex flex-col">
                        <span class="font-headline-md text-headline-md tracking-tight text-on-surface font-bold">
                            Aura<span class="text-secondary font-extrabold">Material</span>
                        </span>
                    </div>

                </div>

                <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-surface-container border border-surface-container-high text-on-surface">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-label-md text-label-md font-medium">Online</span>
                </div>

            </header>

            <!-- JUDUL LOGIN -->
            <section class="mt-4 mb-4">

                <h1 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface font-bold">
                    Masuk ke Sistem Aura Material
                </h1>

                <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                    Pilih peran operasional untuk mulai transaksi toko dan logistik gudang.
                </p>

            </section>

            <!-- FORM LOGIN -->
            <form method="POST" action="{{ route('login') }}" id="login-form">

                @csrf

                <!-- ERROR MESSAGE -->
                @if ($errors->any())
                    <div class="mb-3 text-sm font-medium text-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="bg-surface-container-lowest rounded-xl p-4 shadow-sm border border-surface-container-high flex flex-col gap-4">

                    <!-- USERNAME -->
                    <div class="flex flex-col gap-1.5">

                        <label class="font-label-lg text-label-lg text-on-surface font-semibold" for="username">
                            ID Karyawan / No. HP
                        </label>

                        <div class="relative flex items-center">

                            <span class="material-symbols-outlined text-on-surface-variant absolute left-3 text-[20px]">
                                badge
                            </span>

                            <input
                                class="w-full h-12 pl-10 pr-3 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md outline-none border border-outline-variant/40 focus:border-secondary focus:bg-surface-container transition-colors"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                autocomplete="username"
                                placeholder="Masukkan ID Karyawan / No. HP"
                                type="text"
                                required
                                autofocus
                            >

                        </div>

                        @error('username')
                            <p class="text-sm text-error">{{ $message }}</p>
                        @enderror

                    </div>

                    <!-- PASSWORD / PIN -->
                    <div class="flex flex-col gap-1.5">

                        <div class="flex items-center justify-between">

                            <label class="font-label-lg text-label-lg text-on-surface font-semibold" for="password">
                                PIN Keamanan Toko / Sandi
                            </label>

                            <button
                                class="font-label-md text-label-md text-on-surface-variant hover:text-on-surface flex items-center gap-1 transition-colors"
                                id="toggle-pin"
                                type="button"
                            >
                                <span class="material-symbols-outlined text-[16px]" id="eye-icon">
                                    visibility
                                </span>

                                <span id="eye-text">Lihat</span>
                            </button>

                        </div>

                        <div class="relative flex items-center">

                            <span class="material-symbols-outlined text-on-surface-variant absolute left-3 text-[20px]">
                                lock
                            </span>

                            <input
                                class="w-full h-12 pl-10 pr-20 rounded-lg bg-surface-container-low text-on-surface font-body-md tracking-[0.15em] outline-none border border-outline-variant/40 focus:border-secondary focus:bg-surface-container transition-colors"
                                id="password"
                                name="password"
                                autocomplete="current-password"
                                placeholder="Masukkan PIN / Password"
                                type="password"
                                required
                            >

                            <div class="absolute right-3 flex items-center">
                                <span class="font-label-md text-label-md px-1.5 py-0.5 rounded bg-surface-container text-on-surface-variant font-mono font-bold">
                                    PASSWORD
                                </span>
                            </div>

                        </div>

                        @error('password')
                            <p class="text-sm text-error">{{ $message }}</p>
                        @enderror

                    </div>

                    <!-- PIN CEPAT & BIOMETRIK -->
                    <button
                        class="flex-1 h-11 px-3 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md flex items-center justify-center gap-2 border border-outline-variant/30 transition-colors active:scale-[0.98]"
                        id="btn-quick"
                        type="button"
                    >

                        <span class="material-symbols-outlined text-secondary text-[18px]">
                            fingerprint
                        </span>

                        <span class="font-bold">
                            PIN Cepat &amp; Biometrik Gudang
                        </span>

                    </button>

                    <!-- TOMBOL MASUK -->
                    <button
                        class="w-full h-12 rounded-lg bg-slate-900 hover:bg-[#0f172a] text-on-primary font-title-md text-title-md flex items-center justify-center gap-2 shadow-sm transition-all active:scale-[0.99] mt-0.5 border border-slate-800"
                        id="submit-btn"
                        type="submit"
                    >

                        <span class="font-bold tracking-tight">
                            Masuk
                        </span>

                        <span class="material-symbols-outlined text-[20px]">
                            arrow_forward
                        </span>

                    </button>

                </div>

            </form>

            <!-- FOOTER -->
            <footer class="mt-6 flex flex-col items-center justify-center text-center px-4">
                <p class="text-xs text-on-surface-variant">
                    © {{ date('Y') }} Aura Material. All rights reserved.
                </p>
            </footer>

        </div>

    </div>

</main>

</body>
</html>
```