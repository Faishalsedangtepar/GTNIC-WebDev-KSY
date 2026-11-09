
{{-- Navbar kasir: dipakai halaman kasir, riwayat, dan pembayaran.
     Ubah di file ini, semua halaman ikut berubah. --}}

<header class="fixed top-0 left-0 right-0 w-full z-40 pt-[env(safe-area-inset-top,0px)] bg-[#ffffff]/90 backdrop-blur-xl border-b border-[#e5eeff]">

    <div class="h-16 w-full px-3 sm:px-4 flex items-center justify-between gap-2 sm:gap-3">

        {{-- Kiri: logo + nama toko --}}
        <div class="flex items-center gap-2 sm:gap-3 min-w-0">


<div class="w-10 h-10 shrink-0 rounded-[0.5rem] bg-transparent flex items-center justify-center overflow-hidden">

    <img
        src="{{ asset('images/ud.tentrem1.png') }}"
        alt="UD Tentrem"
        class="w-full h-full object-contain"
    >

</div>


            <span class="font-bold text-sm min-[400px]:text-base text-[#0b1c30] leading-tight tracking-tight truncate">
                UD.Tentrem
            </span>

        </div>


        {{-- Kanan: tombol keluar, nama kasir, foto --}}
        <div class="ml-auto shrink-0 flex items-center gap-1.5 sm:gap-2">

            {{-- Tombol logout --}}
            <form
                method="POST"
                action="{{ route('logout') }}"
                onsubmit="return confirm('Keluar dari akun?')"
            >
                @csrf

                <button
                    type="submit"
                    aria-label="Keluar"
                    class="h-10 px-3 rounded-full border border-[#c6c6cd]/40 bg-white text-[#45464d] hover:bg-[#ffdad6] hover:text-[#ba1a1a] hover:border-[#ba1a1a]/30 active:scale-95 transition-all flex items-center gap-1.5 text-xs font-semibold"
                >
                    <span class="material-symbols-outlined text-[20px]">
                        logout
                    </span>

                    <span class="hidden sm:inline">
                        Keluar
                    </span>
                </button>

            </form>


            {{-- Nama kasir --}}
            <div class="h-10 px-2.5 sm:px-3 min-w-0 bg-[#e5eeff] rounded-full flex items-center gap-1.5 border border-[#c6c6cd]/40">

                <span class="shrink-0 w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>

                <span class="max-w-[4.5rem] min-[400px]:max-w-[7rem] sm:max-w-[12rem] truncate text-xs text-[#0b1c30] font-semibold">
                    {{ auth()->user()->name ?? 'Kasir' }}
                </span>

            </div>


            {{-- Foto profil --}}
<div class="w-8 h-8 shrink-0 rounded-full bg-[#000000] flex items-center justify-center" aria-hidden="true">
  <span class="material-symbols-outlined text-[#ffffff] text-[18px]">person</span>
</div>

        </div>

    </div>

</header>



{{-- Pengganti tinggi navbar yang fixed, supaya isi halaman tidak tertutup --}}
<div aria-hidden="true" class="h-[calc(4rem+env(safe-area-inset-top,0px))] shrink-0"></div>
