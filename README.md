# UD Tentrem

Sistem kasir dan pencatatan untuk toko material bangunan UD Tentrem, Gamping, Sleman.

Dengan adanya sistem ini, nota tidak lagi ditulis tangan, stok tidak lagi dihitung dari ingatan, dan uang yang masuk hari ini bisa dicocokkan dengan catatan

![Tampilan UD Tentrem](public/images/ud.tentrem1.png)


Proyek ini dibuat berdasarkan kebutuhan nyata, dari sebuah Usaha Dagang di Gamping, Sleman. Bernama UD Tentrem, UD ini masih menggunakan ingatan untuk mencatat stok, dan pemasukan

Menjual material bangunan itu rumit, memiliki sangat banyak cara. Pembeli beli semen dua sak, tapi minta sekalian dikirim pakai pickup. Ada yang beli pasir setengah kubik. Ada mandor yang bayar DP dulu, sisanya nanti kalau barang sudah sampai. Ada ongkos kuli muat yang tidak ada di daftar harga mana pun. 

Dengan Web ini, semua hal - hal tersebut dapat ditangani dengan satu sistem

## Siapa saja yang akan memakai web ini

Ada dua jenis pengguna, dan masing-masing hanya dapat melihat apa saja sesuai dengan hak akses.

**Kasir** adalah orang di depan, yang melayani pembeli dari awal sampai nota keluar.

**Admin** adalah pemilik atau pengelola toko, yang butuh gambaran besar seperti berapa omzet, barang apa yang menipis, nota bulan ini mana saja.

## Apa yang bisa dilakukan kasir

- **Cari barang dengan cepat:** Produk dikelompokkan per kategori (semen dan pasir, besi dan baja, cat, pipa, alat tukang, atap dan seng, kayu dan triplek), dan bisa dicari lewat kolom pencarian.
- **Isi keranjang:** Jumlahnya boleh desimal, jadi pasir 0,5 kubik atau paku 2,5 kg tidak jadi masalah.
- **Catat biaya di luar barang:** Ongkir, kuli muat, atau apa pun yang tidak ada di katalog bisa ditambahkan sebagai item sendiri dengan harga bebas.
- **Stok dicek otomatis:** Kasir tidak bisa menjual lebih banyak dari yang ada. Kalau stok tinggal 8 lembar seng, nota 10 lembar tidak akan lolos.
- **Pilih cara kirim:** Truk, pickup, atau diambil sendiri. Ongkirnya langsung ikut terhitung, dan nama, nomor HP, serta alamat pembeli diminta kalau barangnya memang harus diantar.
- **Bayar lunas atau DP:** Untuk DP, jumlahnya dihitung otomatis 30% dari harga barang. Kasir tidak perlu menghitung sendiri, jadi tidak ada kesempatan salah ketik angka.
- **Kembalian dihitung sendiri:** Masukkan uang yang diterima, sisanya muncul.
- **Cetak struk:** Lengkap dengan nama toko dan rincian belanja.
- **Lihat riwayat nota:** Bisa disaring berdasarkan status (lunas, DP, belum dibayar), tanggal, atau nama pembeli.
- **Melunasi nota DP:** Waktu pembeli datang membayar sisanya, tinggal buka notanya dan tandai lunas. Tidak perlu bikin nota baru.
- **Rekap kas harian:** Di akhir shift, kasir bisa melihat dan mencetak berapa uang yang masuk hari itu, dipisah antara pembayaran lunas, DP, dan pelunasan. Jadi pas menghitung isi laci, memiliki angka pembanding.

## Apa yang bisa dilihat admin

- **Laporan pendapatan dan laba kotor:** termasuk sumbangan omzet tiap kategori barang.
- **Data stok barang:** dengan penanda mana yang tersedia, menipis, atau habis.
- **Riwayat nota bulanan:** bisa dibuka satu per satu rinciannya, dan dicetak sebagai rekap.

## Tim

Dikerjakan oleh:

1. Yoga Aditya Pratama (UTY)
2. Dani Alviansyah Herlambang (UTY)
3. Faishal Akbar Pangestu (UTY)
