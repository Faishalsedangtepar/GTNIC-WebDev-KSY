// Logika halaman Data Stok Barang (admin).
// Pencarian, filter kategori, dan paginasi dikerjakan server lewat query string (?q=&kategori=&per=&page=).
// JS di sini hanya membuka/menutup modal dan mengisi form dari data baris yang diklik.

const MODAL_ID = { add: 'modalAdd', edit: 'modalEdit', adjust: 'modalAdjust', delete: 'modalDelete', stok: 'modalStok' };

const el = (id) => document.getElementById(id);

// 12.5 -> "12,5" ; 1200 -> "1.200"
function angka(n) {
  return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(Number(n) || 0);
}

function openModal(type, data = null) {
  if (type === 'edit' && data) {
    el('formEdit').action = el('formEdit').dataset.base + '/' + data.id;
    el('editSku').value = data.sku || '';
    el('editNama').value = data.nama || '';
    el('editBrand').value = data.brand || '';
    el('editKategori').value = data.kategori_id;
    el('editSatuan').value = data.satuan_id;
    el('editStokMin').value = data.stok_minimum;
    el('editBeli').value = data.beli;
    el('editJual').value = data.jual;
    el('editStokInfo').textContent = angka(data.stok) + ' ' + (data.satuan || '');
  } else if (type === 'adjust' && data) {
    el('formAdjust').action = el('formAdjust').dataset.base + '/' + data.id + '/stok';
    el('formAdjust').reset();
    el('modalAdjustSub').textContent = (data.sku || '') + ' - ' + (data.nama || '');
    el('adjustCurrentStock').textContent = angka(data.stok) + ' ' + (data.satuan || '');
  } else if (type === 'delete' && data) {
    el('formDelete').action = el('formDelete').dataset.base + '/' + data.id;
    el('deleteTargetText').textContent = 'SKU: ' + (data.sku || '') + ' (' + (data.nama || '') + ')';
  } else if (type === 'add') {
    el('modalAdd').querySelector('form').reset();
  } else if (type === 'stok') {
    // Tambah stok cepat: barang dipilih dari kotak pencarian, bukan dari baris tabel
    el('formStok').reset();
    el('formStok').removeAttribute('action');
    el('stokInfo').textContent = '-';
    el('stokCari').setCustomValidity('');
    setTimeout(() => el('stokCari').focus(), 50);
  }

  el(MODAL_ID[type]).classList.remove('hidden');
}

function closeModal(type) {
  el(MODAL_ID[type]).classList.add('hidden');
}

// Modal "Tambah Stok Barang": cocokkan teks yang diketik dengan daftar barang (datalist)
function pilihBarangStok() {
  const cari = el('stokCari');
  const form = el('formStok');
  const teks = cari.value.trim();
  const opsi = Array.from(document.querySelectorAll('#daftarProduk option')).find((o) => o.value === teks);

  if (opsi) {
    form.action = form.dataset.base + '/' + opsi.dataset.id + '/stok';
    el('stokInfo').textContent = angka(opsi.dataset.stok) + ' ' + opsi.dataset.satuan;
    cari.setCustomValidity('');
  } else {
    form.removeAttribute('action');
    el('stokInfo').textContent = '-';
    // Kosong: biarkan pesan "required" bawaan browser. Terisi tapi tidak cocok: minta pilih dari daftar.
    cari.setCustomValidity(teks === '' ? '' : 'Pilih barang dari daftar yang muncul.');
  }
}

if (el('stokCari')) el('stokCari').addEventListener('input', pilihBarangStok);

// Tutup semua modal dengan tombol Escape
document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') Object.keys(MODAL_ID).forEach(closeModal);
});

// Dipanggil dari atribut onclick di HTML, jadi harus global
Object.assign(window, { openModal, closeModal });