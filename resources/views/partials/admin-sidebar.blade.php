{{-- Sidebar admin. Menu diatur di blok <nav> di bawah (route, ikon, label). --}}
<aside id="mainSidebar"
       class="fixed left-0 top-0 h-full w-64 bg-[#131b2e] z-50 flex flex-col justify-between select-none -translate-x-full lg:translate-x-0 transition-transform duration-200 shadow-xl lg:shadow-none overflow-y-auto">
  <div class="flex flex-col">
    <div class="h-16 px-[1.25rem] flex items-center justify-between border-b border-[#000000]/20">
      <div class="flex items-center gap-[0.75rem]">
        <div class="w-9 h-9 rounded bg-[#a73a00] flex items-center justify-center text-[#ffffff] font-bold">AM</div>
        <div class="flex flex-col">
          <span class="text-[15px] font-semibold text-[#f8f9ff] tracking-tight leading-none">UD.Tentrem</span>
          <span class="text-[11px] leading-[14px] font-bold text-[#7c839b] uppercase tracking-wider mt-0.5">Toko Bangunan</span>
        </div>
      </div>
      <button type="button" aria-label="Tutup menu" onclick="closeSidebar()"
              class="lg:hidden text-[#7c839b] hover:text-[#f8f9ff] flex items-center justify-center p-1 rounded">
        <span class="material-symbols-outlined text-[22px]">close</span>
      </button>
    </div>
    <div class="px-[0.75rem] pt-[1.25rem] pb-[0.25rem]"></div>
    <nav class="flex flex-col gap-1 px-[0.5rem]">
      <a href="{{ route('admin.dashboard') }}"
         class="{{ request()->routeIs('admin.dashboard') ? 'flex items-center justify-between px-[0.75rem] py-[0.5rem] rounded-[0.25rem] transition-colors bg-[#fd651e] text-[#ffffff] font-bold' : 'flex items-center justify-between px-[0.75rem] py-[0.5rem] rounded-[0.25rem] transition-colors text-[#7c839b] hover:bg-[#213145] hover:text-[#f8f9ff]' }}"
         @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>
        <div class="flex items-center gap-[0.75rem]">
          <span class="material-symbols-outlined text-[20px]">inventory_2</span>
          <span class="text-[15px] leading-[20px] font-semibold">Laporan Keuangan</span>
        </div>
      </a>
      <a href="{{ route('admin.riwayatbulanan') }}"
         class="{{ request()->routeIs('admin.riwayatbulanan') ? 'flex items-center justify-between px-[0.75rem] py-[0.5rem] rounded-[0.25rem] transition-colors bg-[#fd651e] text-[#ffffff] font-bold' : 'flex items-center justify-between px-[0.75rem] py-[0.5rem] rounded-[0.25rem] transition-colors text-[#7c839b] hover:bg-[#213145] hover:text-[#f8f9ff]' }}"
         @if(request()->routeIs('admin.riwayatbulanan')) aria-current="page" @endif>
        <div class="flex items-center gap-[0.75rem]">
          <span class="material-symbols-outlined text-[20px]">receipt_long</span>
          <span class="text-[15px] leading-[20px] font-semibold">Riwayat Nota</span>
        </div>
      </a>
      <a href="{{ route('admin.laporanstok') }}"
         class="{{ request()->routeIs('admin.laporanstok') ? 'flex items-center justify-between px-[0.75rem] py-[0.5rem] rounded-[0.25rem] transition-colors bg-[#fd651e] text-[#ffffff] font-bold' : 'flex items-center justify-between px-[0.75rem] py-[0.5rem] rounded-[0.25rem] transition-colors text-[#7c839b] hover:bg-[#213145] hover:text-[#f8f9ff]' }}"
         @if(request()->routeIs('admin.laporanstok')) aria-current="page" @endif>
        <div class="flex items-center gap-[0.75rem]">
          <span class="material-symbols-outlined text-[20px]">analytics</span>
          <span class="text-[15px] leading-[20px] font-semibold">Data Stok Barang</span>
        </div>
      </a>
    </nav>
  </div>
  <div class="p-[0.75rem] border-t border-[#000000]/20"></div>
</aside>