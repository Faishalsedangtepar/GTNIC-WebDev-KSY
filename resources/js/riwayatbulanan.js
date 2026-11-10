// Halaman Riwayat Nota (admin).
// Daftar, filter bulan/status/pencarian, dan paginasi dikerjakan server (query string).
// JS di sini hanya untuk modal rincian: mengambil JSON dari GET /admin/nota/{id} lalu menampilkannya.

const el = (id) => document.getElementById(id);

const rp = (n) => 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(Number(n) || 0);
const angka = (n) => new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(Number(n) || 0);

// Hindari HTML injection dari data yang diketik kasir (nama, alamat, catatan)
const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

const ARMADA = { ambil_sendiri: 'Ambil Sendiri di Toko', pickup: 'Kirim Pickup', truk: 'Kirim Truk' };
const STATUS = { lunas: 'Lunas', dp: 'DP', belum_dibayar: 'Belum Dibayar' };
const JENIS = { dp: 'DP', pelunasan: 'Pelunasan', lunas: 'Lunas' };

function waktu(iso, tz) {
  const d = new Date(iso);
  const tgl = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', timeZone: tz });
  const jam = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false, timeZone: tz }).replace('.', ':');
  return tgl + ', ' + jam + ' WIB';
}

function barisRingkas(label, nilai, tebal = false) {
  return `<div class="flex justify-between ${tebal ? 'font-bold text-[16px]' : 'text-[13px]'}"><span class="${tebal ? '' : 'text-[#45464d]'}">${label}</span><span>${nilai}</span></div>`;
}

let notaAktif = null;   // data nota yang sedang dibuka di modal
let perluMuat = false;  // true kalau ada pelunasan, supaya tabel dimuat ulang saat modal ditutup

function notif(pesan, galat = false) {
  const e = el('dmNotif');
  e.textContent = pesan;
  e.className = 'mx-5 mt-4 px-3 py-2 rounded-[0.5rem] text-[13px] font-semibold ' + (galat ? 'bg-[#ffdad6] text-[#93000a]' : 'bg-[#d9f7e4] text-[#0f5132]');
}

function isiDetail(n, tz) {
  notaAktif = n;
  el('dmNomor').textContent = n.nomor;
  el('dmStatus').textContent = STATUS[n.status_bayar] || n.status_bayar;
  el('dmMeta').textContent = waktu(n.tanggal, tz) + '  •  Kasir ' + n.kasir;
  el('dmCetak').href = el('detailModal').dataset.base + '/' + n.id + '/cetak';

  // Tombol lunaskan hanya muncul kalau masih ada sisa tagihan (nota DP)
  const btnLunasi = el('dmLunasi');
  if (n.sisa > 0) {
    el('dmLunasiLabel').textContent = 'Lunaskan Sekarang (' + rp(n.sisa) + ')';
    btnLunasi.classList.remove('hidden');
    btnLunasi.classList.add('flex');
  } else {
    btnLunasi.classList.add('hidden');
    btnLunasi.classList.remove('flex');
  }

  const subtotal = n.items.reduce((a, i) => a + i.subtotal, 0);

  const kontak = [];
  if (n.no_hp) kontak.push(`<div class="flex items-center gap-2 text-[13px]"><span class="material-symbols-outlined text-[18px] text-[#76777d]">call</span>${esc(n.no_hp)}</div>`);
  if (n.alamat_pengiriman) {
    const lokasi = n.catatan_lokasi ? ` (${esc(n.catatan_lokasi)})` : '';
    kontak.push(`<div class="flex items-start gap-2 text-[13px] text-[#45464d]"><span class="material-symbols-outlined text-[18px] text-[#76777d]">location_on</span><span>${esc(n.alamat_pengiriman)}${lokasi}</span></div>`);
  }

  const barang = n.items.map((i) => `
    <div class="flex justify-between gap-3">
      <div><div class="font-semibold text-[14px]">${esc(i.nama)}</div>
      <div class="text-[12px] text-[#76777d]">${angka(i.qty)} ${esc(i.satuan)} × ${rp(i.harga)}</div></div>
      <div class="font-bold text-[14px] whitespace-nowrap">${rp(i.subtotal)}</div>
    </div>`).join('');

  const bayar = n.pembayaran.length
    ? `<div class="flex flex-col gap-1 pt-3 border-t border-[#c6c6cd]/40">
        <div class="text-[11px] font-bold uppercase tracking-wider text-[#76777d]">Riwayat Pembayaran</div>
        ${n.pembayaran.map((p) => `<div class="flex justify-between text-[12px] text-[#45464d]"><span>${esc(JENIS[p.jenis] || p.jenis)} • ${waktu(p.dibayar_pada, tz)}</span><span class="font-semibold">${rp(p.jumlah)}</span></div>`).join('')}
      </div>`
    : '';

  el('dmBody').innerHTML = `
    <div class="rounded-xl bg-[#eff4ff] border border-[#c6c6cd]/40 p-4 flex flex-col gap-2">
      <div class="flex items-center justify-between">
        <span class="text-[11px] font-bold uppercase tracking-wider text-[#45464d]">Data Pelanggan / Proyek</span>
        <span class="text-[11px] font-bold text-[#a73a00]">${esc(ARMADA[n.armada] || n.armada || '')}</span>
      </div>
      <div class="text-[16px] font-bold">${esc(n.nama_pembeli || 'Pembeli Umum')}</div>
      ${kontak.join('')}
    </div>
    <div>
      <div class="flex justify-between text-[11px] font-bold uppercase tracking-wider text-[#45464d] pb-2 border-b border-[#c6c6cd]/40"><span>Rincian Material Bangunan</span><span>${n.items.length} Macam Barang</span></div>
      <div class="flex flex-col gap-3 pt-3">${barang}</div>
    </div>
    <div class="rounded-xl bg-[#eff4ff] border border-[#c6c6cd]/40 p-4 flex flex-col gap-2">
      ${barisRingkas('Subtotal Material', rp(subtotal))}
      ${barisRingkas('Ongkos Kirim', rp(n.ongkir))}
      ${barisRingkas('Proteksi Terpal', rp(n.biaya_proteksi))}
      <div class="border-t border-[#c6c6cd]/50 my-1"></div>
      ${barisRingkas('Total Tagihan', rp(n.total), true)}
      ${barisRingkas('Sudah Dibayar', rp(n.dibayar))}
      ${barisRingkas('Sisa Tagihan', rp(n.sisa))}
      ${bayar}
    </div>
    ${n.catatan_sopir ? `<div class="text-[12px] text-[#45464d]"><b>Catatan sopir:</b> ${esc(n.catatan_sopir)}</div>` : ''}`;
}

async function bukaDetail(id) {
  const modal = el('detailModal');
  el('dmNotif').className = 'hidden';
  el('dmLunasi').classList.add('hidden');
  el('dmLunasi').classList.remove('flex');
  el('dmNomor').textContent = 'Memuat...';
  el('dmStatus').textContent = '';
  el('dmMeta').textContent = '';
  el('dmBody').innerHTML = '<div class="py-10 text-center text-[#76777d] text-[13px]">Memuat rincian nota...</div>';
  modal.classList.remove('hidden');

  try {
    const res = await fetch(modal.dataset.base + '/' + id, { headers: { Accept: 'application/json' } });
    if (!res.ok) throw new Error('HTTP ' + res.status);
    isiDetail(await res.json(), modal.dataset.tz);
  } catch (e) {
    el('dmNomor').textContent = 'Gagal memuat';
    el('dmBody').innerHTML = '<div class="py-10 text-center text-[#93000a] text-[13px]">Rincian nota tidak bisa dimuat. Coba lagi.</div>';
  }
}

async function lunasiNota() {
  const n = notaAktif;
  if (!n || n.sisa <= 0) return;
  if (!confirm('Lunaskan ' + n.nomor + ' sebesar ' + rp(n.sisa) + '?')) return;

  const modal = el('detailModal');
  const tombol = el('dmLunasi');
  tombol.disabled = true;

  try {
    const res = await fetch(modal.dataset.base + '/' + n.id + '/lunasi', {
      method: 'POST',
      headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': modal.dataset.csrf },
      body: '{}',
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error((data.errors && data.errors.jumlah && data.errors.jumlah[0]) || data.message || 'HTTP ' + res.status);

    perluMuat = true;
    isiDetail(data, modal.dataset.tz);
    notif('Nota berhasil dilunasi.');
  } catch (e) {
    notif('Gagal melunasi: ' + e.message, true);
  } finally {
    tombol.disabled = false;
  }
}

function tutupDetail() {
  el('detailModal').classList.add('hidden');
  if (perluMuat) location.reload();   // muat ulang supaya status dan ringkasan di tabel ikut berubah
}

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') tutupDetail();
});

// Dipanggil dari atribut onclick di HTML, jadi harus global
Object.assign(window, { bukaDetail, tutupDetail, lunasiNota });