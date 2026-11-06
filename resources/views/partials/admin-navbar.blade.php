{{-- Navbar admin. Kolom cari tampil di semua halaman, kecuali halaman yang mengisi @section('show_date'). --}}
<header class="fixed top-0 left-0 lg:left-64 right-0 h-16 bg-[#ffffff] shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-40 flex items-center px-4 md:px-[1.25rem] justify-between">
  <div class="flex items-center gap-2">
    <button type="button" aria-label="Menu Navigasi" onclick="toggleSidebar(true)"
            class="lg:hidden h-10 w-10 flex items-center justify-center rounded-[0.25rem] hover:bg-[#eff4ff] text-[#0b1c30] transition-colors">
      <span class="material-symbols-outlined text-[24px]">menu</span>
    </button>
  </div>

  <div class="flex items-center gap-[0.75rem]">
    @hasSection('show_date')
      <div class="hidden md:flex items-center gap-[0.5rem] bg-[#eff4ff] px-[0.75rem] py-[0.25rem] rounded-[0.25rem] text-[#45464d]"><span class="material-symbols-outlined text-[18px]">calendar_today</span><span class="text-[13px] leading-[18px] text-[#0b1c30] font-semibold">{{ now()->locale('id')->translatedFormat('d F Y') }}</span></div>
    @else
      <div class="relative flex items-center">
        <span class="material-symbols-outlined absolute left-3 md:left-[0.75rem] text-[18px] text-[#76777d]">search</span>
        <input id="headerSearchInput" type="text" placeholder="Cari SKU, Barcode, No. Nota [F2]"
               class="h-10 w-44 sm:w-64 md:w-80 pl-9 pr-8 sm:pr-12 rounded-[0.25rem] bg-[#eff4ff] border border-[#c6c6cd] text-[13px] leading-[18px] font-normal text-[#0b1c30] placeholder:text-[#76777d] focus:outline-none focus:border-[#0b1c30] focus:ring-1 focus:ring-[#0b1c30] transition-all">
        <button type="button" onclick="focusSearchInput()"
                class="hidden sm:inline-flex absolute right-[0.5rem] text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#76777d] px-1.5 py-0.5 rounded bg-[#dce9ff] hover:bg-[#d3e4fe] cursor-pointer">F2</button>
      </div>
    @endif

    <div class="hidden sm:block h-6 w-[1px] bg-[#c6c6cd]"></div>

    <div class="flex items-center gap-[0.75rem]">
      <div class="w-8 h-8 rounded-full bg-[#000000] flex items-center justify-center">
        <span class="material-symbols-outlined text-[#ffffff] text-[18px]">person</span>
      </div>
      <div class="hidden sm:flex flex-col text-left">
        <span class="text-[13px] tracking-[0.01em] font-bold text-[#0b1c30] leading-tight">{{ auth()->user()->name ?? 'Admin' }}</span>
        <span class="text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#76777d]">{{ ucfirst(auth()->user()->role ?? 'admin') }}</span>
      </div>
    </div>
  </div>
</header>