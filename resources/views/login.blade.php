
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <title>Login - UD.Tentrem</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/login.js'])

    <style>
html, body {
    width: 100%;
    min-height: 100%;
    margin: 0;
    padding: 0;
}

body {
    min-height: 100vh;
    box-sizing: border-box;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 24px;
    overscroll-behavior: none;
}

.login-decoration {
    position: absolute;
    border-radius: 9999px;
    pointer-events: none;
}
    </style>
</head>

<body class="bg-surface-container-low font-sans text-on-surface">

<main class="w-full max-w-6xl min-h-[650px] my-auto bg-surface-container-lowest rounded-2xl overflow-hidden shadow-xl border border-outline-variant/30 grid grid-cols-1 md:grid-cols-2 relative">
    <!-- =====================================
         BAGIAN KIRI: FORM LOGIN
    ====================================== -->

    <section class="relative order-1 flex flex-col justify-center px-5 py-10 sm:px-10 sm:py-12 lg:px-16 lg:py-14">

        <div class="login-decoration w-28 h-28 bg-secondary-container/10 -top-14 -left-14"></div>

        <div class="relative z-10 w-full max-w-[520px] mx-auto">

            <!-- JUDUL LOGIN -->

            <section class="mb-9">

                <p class="text-secondary font-bold text-xs uppercase tracking-[0.18em] mb-3">
                    Selamat Datang
                </p>

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-on-surface leading-tight">
                    Login
                    <span class="text-secondary">UD.Tentrem</span>
                </h1>

                <p class="text-sm sm:text-base text-on-surface-variant mt-4 leading-relaxed">

                </p>

            </section>


            <!-- FORM LOGIN -->

            <form method="POST" action="{{ route('login') }}" id="login-form">

                @csrf

                <!-- ERROR MESSAGE -->

                @if ($errors->any())
                    <div class="mb-5 p-3 rounded-lg bg-error-container border border-error/20 text-error text-sm font-medium">

                        <div class="flex items-center gap-2">

                            <span class="material-symbols-outlined text-lg">
                                error
                            </span>

                            {{ $errors->first() }}

                        </div>

                    </div>
                @endif


                <div class="flex flex-col gap-6">

                    <!-- USERNAME -->

                    <div class="flex flex-col gap-2">

                        <label
                            class="text-sm font-bold text-on-surface"
                            for="username"
                        >
                            ID Karyawan / No. HP
                        </label>

                        <div class="relative flex items-center">

                            <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-xl pointer-events-none">
                                badge
                            </span>

                            <input
                                class="w-full h-14 pl-11 pr-4 rounded-lg bg-surface-container-low text-on-surface text-sm outline-none border border-outline-variant/50 focus:border-secondary focus:ring-2 focus:ring-secondary/10 transition-all placeholder:text-on-surface-variant/60"
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

<div class="flex flex-col gap-2">

    <label
        class="text-sm font-bold text-on-surface"
        for="password"
    >
        PIN Keamanan Toko / Sandi
    </label>


    <div class="relative flex items-center">

        <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-xl pointer-events-none">
            lock
        </span>

        <input
            class="w-full h-14 pl-11 pr-24 rounded-lg bg-surface-container-low text-on-surface text-sm tracking-[0.15em] outline-none border border-outline-variant/50 focus:border-secondary focus:ring-2 focus:ring-secondary/10 transition-all placeholder:text-on-surface-variant/60"
            id="password"
            name="password"
            autocomplete="current-password"
            placeholder="Masukkan PIN / Password"
            type="password"
            required
        >

        <!-- Tombol lihat / tutup PIN (menggantikan tulisan PASSWORD) -->
        <button
            class="absolute right-3 flex items-center gap-1 px-2 py-1 rounded text-xs font-semibold text-secondary hover:text-on-secondary-container hover:bg-surface-container transition-colors"
            id="toggle-pin"
            type="button"
            aria-label="Tampilkan atau sembunyikan PIN"
            onclick="window.togglePinVisibility()"
        >
            <span class="material-symbols-outlined text-base" id="eye-icon">
                visibility
            </span>

            <span id="eye-text">Lihat</span>
        </button>

    </div>

    @error('password')
        <p class="text-sm text-error">{{ $message }}</p>
    @enderror

</div>


                    <!-- TOMBOL MASUK -->

                    <button
                        class="w-full h-14 rounded-lg bg-primary-container hover:bg-brand-navy text-on-primary font-bold text-sm flex items-center justify-center gap-3 shadow-md transition-all active:scale-[0.99] mt-1"
                        id="submit-btn"
                        type="submit"
                    >

                        <span>
                            Masuk ke Sistem
                        </span>

                        <span class="material-symbols-outlined text-xl">
                            arrow_forward
                        </span>

                    </button>

                </div>

            </form>


            <!-- FOOTER -->

            <footer class="mt-10 pt-5 border-t border-outline-variant/30 text-center">

                <p class="text-xs sm:text-sm text-on-surface-variant">

                </p>

                <p class="text-xs text-on-surface-variant/70 mt-2">

                </p>

            </footer>

        </div>

    </section>


    <!-- =====================================
         BAGIAN KANAN: LOGO UD.TENTREM
         HANYA DISEMBUNYIKAN PADA MOBILE
    ====================================== -->

    <section class="hidden md:flex relative order-2 min-h-[260px] sm:min-h-[320px] md:min-h-full bg-primary-container overflow-hidden items-center justify-center px-6 py-10 sm:px-10 lg:px-14">

        <!-- Dekorasi -->

        <div class="login-decoration w-64 h-64 bg-secondary-container/10 -top-24 -right-24"></div>

        <div class="login-decoration w-80 h-80 border border-white/10 -bottom-40 -left-24"></div>

        <div class="login-decoration w-48 h-48 border border-white/10 top-1/2 right-[-70px]"></div>


        <!-- LOGO UTAMA -->

        <div class="relative z-10 w-full flex flex-col items-center justify-center text-center">

            <div class="w-full max-w-[560px] flex items-center justify-center">

                <img
                    src="{{ asset('images/ud.tentrem1.png') }}"
                    alt="Logo UD Tentrem - Toko Bangunan"
                    class="w-full max-h-[420px] object-contain"
                >

            </div>

            <div class="w-16 h-1 rounded-full bg-secondary-container mt-8"></div>

            <p class="text-white/70 text-xs sm:text-sm mt-5 tracking-wide">
                Sistem Manajemen Toko Bangunan
            </p>

<!-- INFORMASI MODUL SISTEM -->
<ul class="mt-10 justify-center" style="display:grid;grid-template-columns:repeat(2,max-content);gap:1rem 2rem;">

    <li class="flex items-center text-left" style="gap:.625rem;">
        <span class="flex items-center justify-center shrink-0"
              style="width:1.75rem;height:1.75rem;border-radius:.375rem;background:rgba(253,101,30,.12);border:1px solid rgba(253,101,30,.30);color:#fd651e;">
            <span class="material-symbols-outlined" style="font-size:16px;">point_of_sale</span>
        </span>
        <span class="text-sm font-medium" style="color:rgba(255,255,255,.8);">Point of Sale</span>
    </li>

    <li class="flex items-center text-left" style="gap:.625rem;">
        <span class="flex items-center justify-center shrink-0"
              style="width:1.75rem;height:1.75rem;border-radius:.375rem;background:rgba(253,101,30,.12);border:1px solid rgba(253,101,30,.30);color:#fd651e;">
            <span class="material-symbols-outlined" style="font-size:16px;">bar_chart</span>
        </span>
        <span class="text-sm font-medium" style="color:rgba(255,255,255,.8);">Inventory &amp; Reports</span>
    </li>

    <li class="flex items-center text-left" style="gap:.625rem;">
        <span class="flex items-center justify-center shrink-0"
              style="width:1.75rem;height:1.75rem;border-radius:.375rem;background:rgba(253,101,30,.12);border:1px solid rgba(253,101,30,.30);color:#fd651e;">
            <span class="material-symbols-outlined" style="font-size:16px;">account_balance_wallet</span>
        </span>
        <span class="text-sm font-medium" style="color:rgba(255,255,255,.8);">Akun &amp; Keuangan</span>
    </li>

    <li class="flex items-center text-left" style="gap:.625rem;">
        <span class="flex items-center justify-center shrink-0"
              style="width:1.75rem;height:1.75rem;border-radius:.375rem;background:rgba(253,101,30,.12);border:1px solid rgba(253,101,30,.30);color:#fd651e;">
            <span class="material-symbols-outlined" style="font-size:16px;">receipt_long</span>
        </span>
        <span class="text-sm font-medium" style="color:rgba(255,255,255,.8);">Riwayat Nota</span>
    </li>

</ul>
            

        </div>

    </section>

</main>

</body>
</html>
