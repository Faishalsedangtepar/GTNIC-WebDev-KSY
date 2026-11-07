{{-- Tombol "Kembali ke Kasir / Pesanan": dipakai di halaman Riwayat dan Pembayaran.
     Ubah di file ini, kedua halaman ikut berubah.
     Pemakaian   : @include('partials.back-button')
     Beri jarak  : @include('partials.back-button', ['class' => 'mb-3'])
     Tujuan lain : @include('partials.back-button', ['href' => route('kasir.riwayat')]) --}}
<a href="{{ $href ?? route('kasir.dashboard') }}"
   onclick="if (history.length > 1) { history.back(); return false; }"
   aria-label="Kembali ke Kasir / Pesanan"
   class="shrink-0 inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white border border-slate-300 hover:border-slate-400 text-sm font-semibold text-slate-800 hover:text-slate-900 hover:bg-slate-50 shadow-xs transition-all group {{ $class ?? '' }}">
  <svg class="w-5 h-5 text-slate-600 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
  <span class="sm:hidden">Kembali</span>
  <span class="hidden sm:inline">Kembali ke Kasir / Pesanan</span>
</a>