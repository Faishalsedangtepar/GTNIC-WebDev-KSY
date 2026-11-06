// Logika halaman Riwayat Nota
// ==========================================
// 1. DATASET MULTI-BULAN & MULTI-STATUS
// ==========================================
const MONTH_NAMES = {
  '2026-03': 'Maret 2026',
  '2026-04': 'April 2026',
  '2026-05': 'Mei 2026',
  '2026-06': 'Juni 2026'
};

const MONTH_BADGE_PRESETS = {
  '2026-03': '392 Nota',
  '2026-04': '410 Nota',
  '2026-05': '428 Nota',
  '2026-06': 'Berjalan'
};

const TRANSACTIONS_DATA = {
  '2026-05': [
    {
      id: 'NOTA-2026-05-428',
      date: '2026-05-28',
      time: '15:40:12',
      customer: 'Mandor Asep',
      project: 'CV Karya Mandiri - Ruko Jababeka',
      total: 18250000,
      dpPaid: 5000000,
      remaining: 13250000,
      status: 'DP',
      cashier: 'Kasir Hendra',
      deliveryNote: 'DO-2026-05-881',
      deliveryLocation: 'Bongkar muatan di lokasi proyek Ruko Jababeka Blok C No. 4-6. Diterima oleh mandor proyek Asep.',
      items: [
        { name: 'Semen Gresik 50kg', sku: 'SMN-GRS-50', unit: 'Zak', qty: 40, price: 65000, subtotal: 2600000 },
        { name: 'Besi Beton Polos 10mm SNI', sku: 'BSI-PL-10-12M', unit: 'Btg', qty: 120, price: 82500, subtotal: 9900000 },
        { name: 'Pasir Pasang Cor Ekstra', sku: 'PSR-COR-RIT', unit: 'Rit', qty: 2, price: 2875000, subtotal: 5750000 }
      ]
    },
    {
      id: 'NOTA-2026-05-427',
      date: '2026-05-28',
      time: '14:12:05',
      customer: 'Pak Joko',
      project: 'Mandor Ruko Permata',
      total: 4670000,
      dpPaid: 4670000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Hendra',
      deliveryNote: 'DO-2026-05-880',
      deliveryLocation: 'Diantar ke kavling Blok B2 Perumahan Permata Indah.',
      items: [
        { name: 'Semen Holcim (Dynamix) 40kg', sku: 'SMN-HLC-40', unit: 'Zak', qty: 35, price: 54000, subtotal: 1890000 },
        { name: 'Bata Ringan Hebel 7.5cm', sku: 'HBL-75-M3', unit: 'm³', qty: 4, price: 695000, subtotal: 2780000 }
      ]
    },
    {
      id: 'NOTA-2026-05-426',
      date: '2026-05-28',
      time: '11:30:44',
      customer: 'PT Citra Bangun Sejahtera',
      project: 'Kontraktor Utama Pergudangan Delta',
      total: 34800000,
      dpPaid: 34800000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Siska',
      deliveryNote: 'DO-2026-05-879',
      deliveryLocation: 'Gate 2 Kawasan GIIC Blok AA-10 Cikarang Pusat.',
      items: [
        { name: 'Besi Beton Ulir 16mm SNI', sku: 'BSI-UL-16-12M', unit: 'Btg', qty: 150, price: 172000, subtotal: 25800000 },
        { name: 'Wiremesh M8 Standar', sku: 'WRM-M8-LBR', unit: 'Lembar', qty: 15, price: 600000, subtotal: 9000000 }
      ]
    },
    {
      id: 'NOTA-2026-05-425',
      date: '2026-05-27',
      time: '16:55:18',
      customer: 'Toko Barokah Bangunan',
      project: 'Sub-Depot Tambun',
      total: 7420000,
      dpPaid: 7420000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Hendra',
      deliveryNote: 'DO-2026-05-878',
      deliveryLocation: 'Bongkar gudang toko Barokah, Jl. Sultan Hasanudin No. 12.',
      items: [
        { name: 'Cat Tembok Dulux Weathershield 20L', sku: 'CAT-DLX-20L', unit: 'Pail', qty: 3, price: 1450000, subtotal: 4350000 },
        { name: 'Pipa PVC Wavin AW 3 Inch', sku: 'PPA-WVN-3AW', unit: 'Btg', qty: 22, price: 139545, subtotal: 3070000 }
      ]
    },
    {
      id: 'NOTA-2026-05-424',
      date: '2026-05-27',
      time: '13:05:00',
      customer: 'Bpk. H. Rahmat',
      project: 'Pelanggan Langsung (Renovasi Rumah)',
      total: 1730000,
      dpPaid: 1730000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Hendra',
      deliveryNote: 'DO-2026-05-877',
      deliveryLocation: 'Bawa sendiri armada pikap pelanggan.',
      items: [
        { name: 'Keramik Lantai 40x40 Putih Polos', sku: 'KRM-40-PL', unit: 'Dus', qty: 20, price: 65000, subtotal: 1300000 },
        { name: 'Semen Nat Keramik AM53', sku: 'NAT-AM53-1KG', unit: 'Bks', qty: 10, price: 18000, subtotal: 180000 },
        { name: 'Lem Silikon Serbaguna Clear', sku: 'LEM-SLK-CLR', unit: 'Tbg', qty: 5, price: 50000, subtotal: 250000 }
      ]
    },
    {
      id: 'NOTA-2026-05-423',
      date: '2026-05-26',
      time: '10:20:30',
      customer: 'Mandor Ridwan',
      project: 'Proyek Kos Cibitung',
      total: 9850000,
      dpPaid: 3500000,
      remaining: 6350000,
      status: 'DP',
      cashier: 'Kasir Siska',
      deliveryNote: 'DO-2026-05-876',
      deliveryLocation: 'Gg. H. Naim, Cibitung. Pelunasan saat atap terpasang.',
      items: [
        { name: 'Baja Ringan Kanal C75 0.75mm', sku: 'BJA-C75-75', unit: 'Btg', qty: 85, price: 82000, subtotal: 6970000 },
        { name: 'Reng Baja Ringan 0.45mm', sku: 'BJA-RNG-45', unit: 'Btg', qty: 60, price: 48000, subtotal: 2880000 }
      ]
    },
    {
      id: 'NOTA-2026-05-422',
      date: '2026-05-25',
      time: '09:14:45',
      customer: 'Ibu Dewi',
      project: 'Retail Tunai',
      total: 384000,
      dpPaid: 384000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Hendra',
      deliveryNote: 'STR-2026-05-110',
      deliveryLocation: 'Kasir Tunai Konter.',
      items: [
        { name: 'Kran Air Tembok Onda 1/2 Inch', sku: 'KRN-OND-12', unit: 'Pcs', qty: 4, price: 48500, subtotal: 194000 },
        { name: 'Seal Tape TBA 12mm', sku: 'SLT-TBA-12', unit: 'Roll', qty: 10, price: 6000, subtotal: 60000 },
        { name: 'Lem Pipa Isarplas 100gr', sku: 'LEM-ISR-100', unit: 'Tbg', qty: 2, price: 15000, subtotal: 30000 },
        { name: 'Kuas Cat 3 Inch Eterna', sku: 'KUS-ETR-3', unit: 'Pcs', qty: 4, price: 25000, subtotal: 100000 }
      ]
    },
    {
      id: 'NOTA-2026-05-421',
      date: '2026-05-24',
      time: '16:02:11',
      customer: 'Mandor Supriadi',
      project: 'Renovasi Musholla Al-Ikhlas',
      total: 14200000,
      dpPaid: 5000000,
      remaining: 9200000,
      status: 'DP',
      cashier: 'Kasir Siska',
      deliveryNote: 'DO-2026-05-875',
      deliveryLocation: 'Komplek Bumi Anggrek, Babelan.',
      items: [
        { name: 'Batu Split Cor 1/2', sku: 'BAT-SPL-12', unit: 'Rit', qty: 3, price: 2400000, subtotal: 7200000 },
        { name: 'Semen Tiga Roda 50kg', sku: 'SMN-3RD-50', unit: 'Zak', qty: 100, price: 70000, subtotal: 7000000 }
      ]
    },
    {
      id: 'NOTA-2026-05-420',
      date: '2026-05-22',
      time: '11:45:22',
      customer: 'CV Mega Konstruksi',
      project: 'Drainase Perum Grand Surya',
      total: 21500000,
      dpPaid: 21500000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Hendra',
      deliveryNote: 'DO-2026-05-874',
      deliveryLocation: 'Jl. Boulevard Utama Grand Surya Estate.',
      items: [
        { name: 'Gorong-gorong U-Ditch 40x40x120', sku: 'PRC-UD-40', unit: 'Pcs', qty: 50, price: 330000, subtotal: 16500000 },
        { name: 'Tutup Cover U-Ditch HD', sku: 'PRC-CVR-40', unit: 'Pcs', qty: 50, price: 100000, subtotal: 5000000 }
      ]
    },
    {
      id: 'NOTA-2026-05-419',
      date: '2026-05-20',
      time: '08:30:19',
      customer: 'Mandor Heri',
      project: 'Perluasan Bengkel Las',
      total: 5200000,
      dpPaid: 5200000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Siska',
      deliveryNote: 'DO-2026-05-873',
      deliveryLocation: 'Kawasan Kalimalang Tambun.',
      items: [
        { name: 'Pipa Hollow Besi 4x4 Tebal 1.6mm', sku: 'BS-HLW-44', unit: 'Btg', qty: 25, price: 125000, subtotal: 3125000 },
        { name: 'Seng Gelombang Galvalum 3m', sku: 'SNG-GLV-3M', unit: 'Lembar', qty: 25, price: 83000, subtotal: 2075000 }
      ]
    },
    {
      id: 'NOTA-2026-05-418',
      date: '2026-05-18',
      time: '14:20:00',
      customer: 'Mandor Dani',
      project: 'Proyek Cluster Mahoni',
      total: 8900000,
      dpPaid: 3000000,
      remaining: 5900000,
      status: 'DP',
      cashier: 'Kasir Hendra',
      deliveryNote: 'DO-2026-05-872',
      deliveryLocation: 'Kavling Cluster Mahoni No. 14.',
      items: [
        { name: 'Semen Padang 40kg', sku: 'SMN-PDG-40', unit: 'Zak', qty: 100, price: 59000, subtotal: 5900000 },
        { name: 'Pasir Jebrod Cor', sku: 'PSR-JBR-1M3', unit: 'm³', qty: 10, price: 300000, subtotal: 3000000 }
      ]
    },
    {
      id: 'NOTA-2026-05-417',
      date: '2026-05-14',
      time: '10:05:00',
      customer: 'Pak Bambang',
      project: 'Pagar Keliling Tanah',
      total: 6200000,
      dpPaid: 6200000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Hendra',
      deliveryNote: 'DO-2026-05-871',
      deliveryLocation: 'Jl. Raya Setu Cikarang.',
      items: [
        { name: 'Batako Semen Press Masif', sku: 'BTK-PRS-STD', unit: 'Pcs', qty: 1500, price: 3200, subtotal: 4800000 },
        { name: 'Semen Tiga Roda 40kg', sku: 'SMN-3RD-40', unit: 'Zak', qty: 25, price: 56000, subtotal: 1400000 }
      ]
    },
    {
      id: 'NOTA-2026-05-416',
      date: '2026-05-11',
      time: '13:40:50',
      customer: 'PT Wijaya Kreasi',
      project: 'Pabrik Minuman Delta Silicon',
      total: 42100000,
      dpPaid: 42100000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Siska',
      deliveryNote: 'DO-2026-05-870',
      deliveryLocation: 'Delta Silicon V Kav. 2-8.',
      items: [
        { name: 'Besi Hollow Galvanis 5x10', sku: 'BS-HLW-GLV', unit: 'Btg', qty: 80, price: 295000, subtotal: 23600000 },
        { name: 'Plat Besi Hitam 3mm 4x8', sku: 'PLT-BS-3MM', unit: 'Lembar', qty: 15, price: 1233333, subtotal: 18500000 }
      ]
    },
    {
      id: 'NOTA-2026-05-415',
      date: '2026-05-08',
      time: '09:00:15',
      customer: 'Mandor Tono',
      project: 'Renovasi Dapur Cluster Viola',
      total: 3600000,
      dpPaid: 3600000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Hendra',
      deliveryNote: 'DO-2026-05-869',
      deliveryLocation: 'Bumi Anggrek Karangsatria.',
      items: [
        { name: 'Granit Tile 60x60 Motif Marmer', sku: 'GRN-60-MRM', unit: 'Dus', qty: 18, price: 180000, subtotal: 3240000 },
        { name: 'Perekat Granit Mortar Utama MU-400', sku: 'MRT-MU400-25', unit: 'Zak', qty: 3, price: 120000, subtotal: 360000 }
      ]
    },
    {
      id: 'NOTA-2026-05-414',
      date: '2026-05-04',
      time: '15:10:30',
      customer: 'Mandor Kosim',
      project: 'Pondasi Gudang Logistik',
      total: 16500000,
      dpPaid: 6500000,
      remaining: 10000000,
      status: 'DP',
      cashier: 'Kasir Hendra',
      deliveryNote: 'DO-2026-05-868',
      deliveryLocation: 'Kawasan Lemahabang Cikarang Timur.',
      items: [
        { name: 'Batu Kali Belah Super', sku: 'BAT-KALI-RIT', unit: 'Rit', qty: 5, price: 1900000, subtotal: 9500000 },
        { name: 'Semen Gresik 50kg', sku: 'SMN-GRS-50', unit: 'Zak', qty: 100, price: 70000, subtotal: 7000000 }
      ]
    },
    {
      id: 'NOTA-2026-05-413',
      date: '2026-05-02',
      time: '11:15:00',
      customer: 'Ibu Lilis Suryani',
      project: 'Pembangunan Toko Kelontong',
      total: 12200000,
      dpPaid: 12200000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Siska',
      deliveryNote: 'DO-2026-05-867',
      deliveryLocation: 'Simpang Tiga Sukatani.',
      items: [
        { name: 'Baja Ringan C75 0.75 SNI', sku: 'BJA-C75-75', unit: 'Btg', qty: 70, price: 82000, subtotal: 5740000 },
        { name: 'Genteng Metal Pasir Merah', sku: 'GNT-MTL-PSR', unit: 'Lembar', qty: 180, price: 32000, subtotal: 5760000 },
        { name: 'Baut Roofing SDS 5cm', sku: 'BAU-RFG-50', unit: 'Dus', qty: 7, price: 100000, subtotal: 700000 }
      ]
    }
  ],
  '2026-04': [
    {
      id: 'NOTA-2026-04-410',
      date: '2026-04-30',
      time: '16:10:00',
      customer: 'Mandor Yusuf',
      project: 'Proyek Ruko Sentra Timur',
      total: 22400000,
      dpPaid: 22400000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Hendra',
      deliveryNote: 'DO-2026-04-790',
      deliveryLocation: 'Sentra Timur Commercial Blok D.',
      items: [
        { name: 'Besi Beton Polos 12mm SNI', sku: 'BSI-PL-12-12M', unit: 'Btg', qty: 120, price: 120000, subtotal: 14400000 },
        { name: 'Semen Holcim 50kg', sku: 'SMN-HLC-50', unit: 'Zak', qty: 115, price: 69565, subtotal: 8000000 }
      ]
    },
    {
      id: 'NOTA-2026-04-409',
      date: '2026-04-28',
      time: '13:22:15',
      customer: 'CV Bangun Prima',
      project: 'Kantor Cabang Lippo',
      total: 15300000,
      dpPaid: 7000000,
      remaining: 8300000,
      status: 'DP',
      cashier: 'Kasir Siska',
      deliveryNote: 'DO-2026-04-789',
      deliveryLocation: 'Kawasan Niaga Lippo Cikarang No. 8.',
      items: [
        { name: 'Gypsum Board 9mm Jayaboard', sku: 'GPS-JYA-9MM', unit: 'Lembar', qty: 120, price: 78000, subtotal: 9360000 },
        { name: 'Rangka Hollow Meni 2x4 & 4x4', sku: 'HLW-MENI-PAKET', unit: 'Paket', qty: 1, price: 5940000, subtotal: 5940000 }
      ]
    },
    {
      id: 'NOTA-2026-04-408',
      date: '2026-04-25',
      time: '10:45:00',
      customer: 'Pak Hendro',
      project: 'Renovasi Dak Rumah',
      total: 8650000,
      dpPaid: 8650000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Hendra',
      deliveryNote: 'DO-2026-04-788',
      deliveryLocation: 'Perum Grand Wisata Cluster Festive.',
      items: [
        { name: 'Semen Gresik 50kg', sku: 'SMN-GRS-50', unit: 'Zak', qty: 50, price: 67000, subtotal: 3350000 },
        { name: 'Pasir Cor Bangka Super', sku: 'PSR-BNG-RIT', unit: 'Rit', qty: 1, price: 3100000, subtotal: 3100000 },
        { name: 'Waterproofing Aquaproof 20kg', sku: 'WTP-AQP-20KG', unit: 'Pail', qty: 2, price: 1100000, subtotal: 2200000 }
      ]
    },
    {
      id: 'NOTA-2026-04-407',
      date: '2026-04-20',
      time: '15:10:30',
      customer: 'Mandor Sanusi',
      project: 'Pembangunan Pos Ronda & Gapura',
      total: 4200000,
      dpPaid: 4200000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Siska',
      deliveryNote: 'DO-2026-04-787',
      deliveryLocation: 'RW 08 Kelurahan Telaga Asih.',
      items: [
        { name: 'Batu Merah Jumbo Oven', sku: 'BT-MRH-JMB', unit: 'Pcs', qty: 3500, price: 900, subtotal: 3150000 },
        { name: 'Semen Padang 40kg', sku: 'SMN-PDG-40', unit: 'Zak', qty: 18, price: 58333, subtotal: 1050000 }
      ]
    },
    {
      id: 'NOTA-2026-04-406',
      date: '2026-04-12',
      time: '09:30:00',
      customer: 'PT Pilar Abadi Mandiri',
      project: 'Workshops Fabrikasi Logam',
      total: 38900000,
      dpPaid: 15000000,
      remaining: 23900000,
      status: 'DP',
      cashier: 'Kasir Hendra',
      deliveryNote: 'DO-2026-04-786',
      deliveryLocation: 'Zona Industri Jababeka 3.',
      items: [
        { name: 'Besi WF 150x75x5x7 12m', sku: 'BSI-WF-150', unit: 'Btg', qty: 10, price: 2890000, subtotal: 28900000 },
        { name: 'Plat Besi Bordes 3.2mm', sku: 'PLT-BRD-32', unit: 'Lembar', qty: 10, price: 1000000, subtotal: 10000000 }
      ]
    }
  ],
  '2026-03': [
    {
      id: 'NOTA-2026-03-392',
      date: '2026-03-31',
      time: '16:40:00',
      customer: 'Mandor Hasan',
      project: 'Perumahan Griya Indah',
      total: 16800000,
      dpPaid: 16800000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Siska',
      deliveryNote: 'DO-2026-03-650',
      deliveryLocation: 'Kavling C12 Griya Indah.',
      items: [
        { name: 'Semen Gresik 50kg', sku: 'SMN-GRS-50', unit: 'Zak', qty: 120, price: 65000, subtotal: 7800000 },
        { name: 'Pasir Cor Super', sku: 'PSR-COR-RIT', unit: 'Rit', qty: 3, price: 3000000, subtotal: 9000000 }
      ]
    },
    {
      id: 'NOTA-2026-03-391',
      date: '2026-03-27',
      time: '11:15:30',
      customer: 'Toko Sumber Bangunan',
      project: 'Gudang Retail',
      total: 9350000,
      dpPaid: 4000000,
      remaining: 5350000,
      status: 'DP',
      cashier: 'Kasir Hendra',
      deliveryNote: 'DO-2026-03-649',
      deliveryLocation: 'Jl. Pasar Baru No. 40.',
      items: [
        { name: 'Cat Nippon Paint Weatherbond 20L', sku: 'CAT-NPP-20L', unit: 'Pail', qty: 5, price: 1350000, subtotal: 6750000 },
        { name: 'Thinner Super A 5L', sku: 'THN-SPR-5L', unit: 'Galon', qty: 20, price: 130000, subtotal: 2600000 }
      ]
    },
    {
      id: 'NOTA-2026-03-390',
      date: '2026-03-15',
      time: '14:20:00',
      customer: 'Bpk. Suherman',
      project: 'Renovasi Hunian Pribadi',
      total: 3450000,
      dpPaid: 3450000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Siska',
      deliveryNote: 'DO-2026-03-648',
      deliveryLocation: 'Taman Aster Blok C No. 9.',
      items: [
        { name: 'Pintu PVC Kamar Mandi Lengkap', sku: 'PNT-PVC-STD', unit: 'Set', qty: 3, price: 450000, subtotal: 1350000 },
        { name: 'Kloset Duduk Toto Eco Washer', sku: 'KLS-TTO-ECO', unit: 'Set', qty: 1, price: 2100000, subtotal: 2100000 }
      ]
    }
  ],
  '2026-06': [
    {
      id: 'NOTA-2026-06-003',
      date: '2026-06-02',
      time: '10:15:00',
      customer: 'Mandor Burhan',
      project: 'Proyek Tahap II Taman Kota',
      total: 12500000,
      dpPaid: 5000000,
      remaining: 7500000,
      status: 'DP',
      cashier: 'Kasir Hendra',
      deliveryNote: 'DO-2026-06-003',
      deliveryLocation: 'Kavling Taman Kota Blok G-5.',
      items: [
        { name: 'Besi Beton Polos 10mm SNI', sku: 'BSI-PL-10-12M', unit: 'Btg', qty: 100, price: 82500, subtotal: 8250000 },
        { name: 'Semen Holcim 50kg', sku: 'SMN-HLC-50', unit: 'Zak', qty: 60, price: 70833, subtotal: 4250000 }
      ]
    },
    {
      id: 'NOTA-2026-06-002',
      date: '2026-06-01',
      time: '15:20:00',
      customer: 'Pak Surya Dinata',
      project: 'Perbaikan Atap Gudang',
      total: 4950000,
      dpPaid: 4950000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Siska',
      deliveryNote: 'DO-2026-06-002',
      deliveryLocation: 'Jl. Rawa Tembaga No. 11.',
      items: [
        { name: 'Seng Spandek 0.35mm x 6m', sku: 'SPN-035-6M', unit: 'Lembar', qty: 15, price: 230000, subtotal: 3450000 },
        { name: 'Baut Spandek 5cm', sku: 'BAU-SPD-50', unit: 'Dus', qty: 15, price: 100000, subtotal: 1500000 }
      ]
    },
    {
      id: 'NOTA-2026-06-001',
      date: '2026-06-01',
      time: '08:45:00',
      customer: 'Mandor Asep',
      project: 'Kelanjutan Finisihing Ruko Jababeka',
      total: 3100000,
      dpPaid: 3100000,
      remaining: 0,
      status: 'LUNAS',
      cashier: 'Kasir Hendra',
      deliveryNote: 'DO-2026-06-001',
      deliveryLocation: 'Ruko Jababeka Blok C.',
      items: [
        { name: 'Cat Dasar Tembok Nippon Alkali', sku: 'CAT-ALK-20L', unit: 'Pail', qty: 2, price: 950000, subtotal: 1900000 },
        { name: 'Plamir Tembok Boyo 20kg', sku: 'PLM-BYO-20', unit: 'Pail', qty: 4, price: 300000, subtotal: 1200000 }
      ]
    }
  ]
};

// ==========================================
// 2. STATE APLIKASI
// ==========================================
let currentMonth = '2026-05';
let currentStatusFilter = 'ALL';
let currentDateRange = 'ALL';
let searchQuery = '';
let currentPage = 1;
let pageSize = 10;
let currentModalReceiptId = null;

// Format Rupiah
function formatRp(num) {
  return 'Rp ' + Number(num).toLocaleString('id-ID');
}

// Toggle Sidebar untuk Mobile


// Fokuskan input saat F2 ditekan


window.addEventListener('keydown', function(e) {
  if (e.key === 'F2') {
    e.preventDefault();
    focusSearchInput();
  } else if (e.key === 'Escape') {
    closeReceiptModal();
    closeMonthlyReportModal();
  }
});

// Ganti Tab Bulan
function switchMonthTab(monthKey) {
  if (!MONTH_NAMES[monthKey]) return;
  currentMonth = monthKey;
  currentPage = 1;

  // Sinkronkan dropdown bulan
  const monthSelect = document.getElementById('monthSelect');
  if (monthSelect) monthSelect.value = monthKey;

  updateMonthTabsUI();
  applyFiltersAndRender();
}

// Handler Dropdown Bulan
function handleMonthSelectChange(monthKey) {
  switchMonthTab(monthKey);
}

// Navigasi Prev/Next Bulan
function navigateMonth(direction) {
  const months = ['2026-03', '2026-04', '2026-05', '2026-06'];
  const currentIndex = months.indexOf(currentMonth);
  let nextIndex = currentIndex + direction;
  if (nextIndex < 0) nextIndex = months.length - 1;
  if (nextIndex >= months.length) nextIndex = 0;
  switchMonthTab(months[nextIndex]);
}

// Update Tampilan Tab Bulan
function updateMonthTabsUI() {
  const tabs = document.querySelectorAll('#monthTabsStrip .month-tab');
  tabs.forEach(tab => {
    const m = tab.getAttribute('data-month');
    const badge = tab.querySelector('span:nth-child(2)');
    if (m === currentMonth) {
      tab.className = 'month-tab px-[0.75rem] py-2 rounded-[0.25rem]  text-[13px] leading-[16px] tracking-[0.01em] bg-[#000000] text-[#ffffff] font-bold shadow-sm whitespace-nowrap flex items-center gap-2 flex-shrink-0';
      if (badge) {
        badge.className = 'px-1.5 py-0.5 rounded bg-[#ffffff]/20 text-[#f8f9ff]  text-[11px] leading-[14px] tracking-[0.04em] font-bold';
        const total = (TRANSACTIONS_DATA[m] || []).length;
        badge.textContent = `${total} Nota • Aktif`;
      }
    } else {
      tab.className = 'month-tab px-[0.75rem] py-2 rounded-[0.25rem]  text-[13px] leading-[16px] tracking-[0.01em] font-semibold text-[#76777d] hover:text-[#0b1c30] hover:bg-[#eff4ff] transition-colors whitespace-nowrap flex items-center gap-2 flex-shrink-0';
      if (badge) {
        badge.className = 'px-1.5 py-0.5 rounded bg-[#dce9ff] text-[#45464d]  text-[11px] leading-[14px] tracking-[0.04em] font-bold';
        const total = (TRANSACTIONS_DATA[m] || []).length;
        badge.textContent = m === '2026-06' ? 'Berjalan' : `${total} Nota`;
      }
    }
  });

  // Perbarui text tanggal filter selector agar selaras
  const dateFilter = document.getElementById('dateRangeFilter');
  if (dateFilter) {
    const monthLabel = MONTH_NAMES[currentMonth] || currentMonth;
    const shortMonth = monthLabel.split(' ')[0];
    dateFilter.options[0].text = `Seluruh Bulan (1 - 31 ${shortMonth})`;
    dateFilter.options[1].text = `Minggu 1 (1 - 7 ${shortMonth})`;
    dateFilter.options[2].text = `Minggu 2 (8 - 14 ${shortMonth})`;
    dateFilter.options[3].text = `Minggu 3 (15 - 21 ${shortMonth})`;
    dateFilter.options[4].text = `Minggu 4 (22 - 31 ${shortMonth})`;
  }
}

// Filter Status
function setStatusFilter(status) {
  currentStatusFilter = status;
  currentPage = 1;
  updateStatusButtonsUI();
  applyFiltersAndRender();
}

function updateStatusButtonsUI() {
  const btnAll = document.getElementById('filterBtnAll');
  const btnLunas = document.getElementById('filterBtnLunas');
  const btnDp = document.getElementById('filterBtnDp');

  const baseInactive = 'status-chip px-3 py-1.5 rounded-md  text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#76777d] hover:text-[#0b1c30] transition-all whitespace-nowrap';
  const baseActive = 'status-chip px-3 py-1.5 rounded-md  text-[11px] leading-[14px] tracking-[0.04em] bg-[#ffffff] text-[#0b1c30] font-bold shadow-sm transition-all whitespace-nowrap';
  const baseDpInactive = 'status-chip px-3 py-1.5 rounded-md  text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#a73a00] hover:text-[#0b1c30] transition-all whitespace-nowrap';

  btnAll.className = currentStatusFilter === 'ALL' ? baseActive : baseInactive;
  btnLunas.className = currentStatusFilter === 'LUNAS' ? baseActive : baseInactive;
  btnDp.className = currentStatusFilter === 'DP' ? baseActive : baseDpInactive;
}

// Pencarian
function handleSearchInput(val) {
  searchQuery = val.trim().toLowerCase();
  const clearBtn = document.getElementById('clearSearchBtn');
  if (clearBtn) {
    clearBtn.classList.toggle('hidden', searchQuery.length === 0);
  }
  currentPage = 1;
  applyFiltersAndRender();
}

function clearSearch() {
  const input = document.getElementById('searchInput');
  if (input) {
    input.value = '';
    handleSearchInput('');
    input.focus();
  }
}

// Header search sync
const headerSearch = document.getElementById('headerSearchInput');
if (headerSearch) {
  headerSearch.addEventListener('input', function(e) {
    const input = document.getElementById('searchInput');
    if (input) {
      input.value = e.target.value;
      handleSearchInput(e.target.value);
    }
  });
}

// Filter Rentang Hari
function handleDateRangeFilter(range) {
  currentDateRange = range;
  currentPage = 1;
  applyFiltersAndRender();
}

// Ukuran Baris
function handlePageSizeChange(size) {
  pageSize = parseInt(size, 10) || 10;
  currentPage = 1;
  applyFiltersAndRender();
}

// Helper filter range hari
function isDateInRange(dateStr, range) {
  if (range === 'ALL') return true;
  const day = parseInt(dateStr.split('-')[2], 10);
  if (range === 'W1') return day >= 1 && day <= 7;
  if (range === 'W2') return day >= 8 && day <= 14;
  if (range === 'W3') return day >= 15 && day <= 21;
  if (range === 'W4') return day >= 22 && day <= 31;
  return true;
}

// ==========================================
// 3. FILTERING & RENDERING UTAMA
// ==========================================
function getFilteredData() {
  const rawList = TRANSACTIONS_DATA[currentMonth] || [];

  return rawList.filter(item => {
    // 1. Status Filter
    if (currentStatusFilter !== 'ALL' && item.status !== currentStatusFilter) {
      return false;
    }

    // 2. Date Range Filter
    if (!isDateInRange(item.date, currentDateRange)) {
      return false;
    }

    // 3. Search Query
    if (searchQuery) {
      const matchId = item.id.toLowerCase().includes(searchQuery);
      const matchCust = item.customer.toLowerCase().includes(searchQuery);
      const matchProj = (item.project || '').toLowerCase().includes(searchQuery);
      const matchItem = item.items.some(it => it.name.toLowerCase().includes(searchQuery) || (it.sku || '').toLowerCase().includes(searchQuery));
      if (!matchId && !matchCust && !matchProj && !matchItem) {
        return false;
      }
    }

    return true;
  });
}

function updateFilterCounts() {
  const rawList = TRANSACTIONS_DATA[currentMonth] || [];
  const totalCount = rawList.length;
  const lunasCount = rawList.filter(i => i.status === 'LUNAS').length;
  const dpCount = rawList.filter(i => i.status === 'DP').length;

  const btnAll = document.getElementById('filterBtnAll');
  const btnLunas = document.getElementById('filterBtnLunas');
  const btnDp = document.getElementById('filterBtnDp');

  if (btnAll) btnAll.textContent = `Semua (${totalCount})`;
  if (btnLunas) btnLunas.textContent = `Lunas (${lunasCount})`;
  if (btnDp) btnDp.textContent = `DP / Tempo (${dpCount})`;
}

function applyFiltersAndRender() {
  updateFilterCounts();
  const filtered = getFilteredData();
  const totalItems = filtered.length;
  const totalPages = Math.max(1, Math.ceil(totalItems / pageSize));

  if (currentPage > totalPages) currentPage = totalPages;

  // Pagination Slice
  const startIndex = (currentPage - 1) * pageSize;
  const pageItems = filtered.slice(startIndex, startIndex + pageSize);

  // Render Tabel Baris
  const tbody = document.getElementById('notaTableBody');
  if (tbody) {
    if (pageItems.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="6" class="py-12 px-4 text-center text-[#76777d]">
            <div class="flex flex-col items-center justify-center gap-2">
              <span class="material-symbols-outlined text-4xl text-[#c6c6cd]">search_off</span>
              <p class="text-[#0b1c30]">Tidak ada nota transaksi yang sesuai</p>
              <p class="text-sm text-[#76777d]">Coba atur kata kunci pencarian atau ubah filter status/bulan.</p>
            </div>
          </td>
        </tr>
      `;
    } else {
      tbody.innerHTML = pageItems.map((tx, idx) => {
        const rowNumber = startIndex + idx + 1;
        const isOdd = idx % 2 === 1;
        const rowBg = isOdd ? 'bg-[#eff4ff]/20' : 'bg-[#ffffff]';
        const formattedDate = formatDateDisplay(tx.date, tx.time);
        
        const statusBadge = tx.status === 'LUNAS' 
          ? `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#e5eeff] text-[#0b1c30] text-[11px] leading-[14px] font-bold uppercase tracking-wider"><span class="material-symbols-outlined text-[13px] text-[#0b1c30]">check_circle</span>LUNAS</span>`
          : `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#ffdbce] text-[#370e00] text-[11px] leading-[14px] font-bold uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-[#a73a00]"></span>DP / Tempo</span>`;

        return `
          <tr class="hover:bg-[#eff4ff]/60 transition-colors ${rowBg} border-b border-[#c6c6cd]/30"
              data-receipt="${tx.id}" data-customer="${tx.customer}" data-status="${tx.status}" data-total="${formatRp(tx.total)}">
            <td class="py-3 px-4 text-center text-[#76777d]">${rowNumber}</td>
            <td class="py-3 px-4">
              <div class="font-bold text-[#0b1c30]">${tx.id}</div>
              <div class="text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#76777d]">${formattedDate}</div>
            </td>
            <td class="py-3 px-4">
              <div class="font-semibold text-[#0b1c30]">${tx.customer}</div>
              <div class="text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#76777d]">${tx.project || '-'}</div>
            </td>
            <td class="py-3 px-4 text-right font-bold text-[#0b1c30] text-[16px]">${formatRp(tx.total)}</td>
            <td class="py-3 px-4 text-center">${statusBadge}</td>
            <td class="py-3 px-4 text-center">
              <button class="h-8 px-3 rounded-[0.25rem] bg-[#dce9ff] hover:bg-[#d3e4fe] text-[#0b1c30] text-[11px] leading-[14px] tracking-[0.04em] font-semibold inline-flex items-center gap-1 transition-colors" onclick="openReceiptModal('${tx.id}')">
                <span class="material-symbols-outlined text-[16px]">visibility</span>
                <span>Detail</span>
              </button>
            </td>
          </tr>
        `;
      }).join('');
    }
  }

  // Render Footer Rekapitulasi
  renderFooterSummary(filtered);

  // Render Pagination Info & Buttons
  renderPagination(totalItems, totalPages);
}

function formatDateDisplay(dateStr, timeStr) {
  // Misal: 2026-05-28 -> 28 Mei 2026 • 15:40
  const parts = dateStr.split('-');
  const day = parts[2];
  const monthIdx = parseInt(parts[1], 10);
  const monthNamesId = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  const monthName = monthNamesId[monthIdx] || parts[1];
  const timeFormatted = timeStr ? timeStr.slice(0, 5) : '';
  return `${day} ${monthName} ${parts[0]} • ${timeFormatted}`;
}

// Render Footer
function renderFooterSummary(data) {
  const countEl = document.getElementById('footerSummaryCount');
  const totalEl = document.getElementById('footerSummaryTotal');
  const pctEl = document.getElementById('footerSummaryPercentage');
  const monthEl = document.getElementById('footerSummaryMonth');

  const totalSum = data.reduce((acc, curr) => acc + curr.total, 0);
  const lunasCount = data.filter(d => d.status === 'LUNAS').length;
  const pct = data.length > 0 ? ((lunasCount / data.length) * 100).toFixed(1) : '100.0';

  if (countEl) countEl.textContent = `${data.length} Transaksi Terarsip`;
  if (totalEl) totalEl.textContent = formatRp(totalSum);
  if (pctEl) pctEl.textContent = `${pct}% Lunas`;
  if (monthEl) monthEl.textContent = MONTH_NAMES[currentMonth] || currentMonth;
}

// Render Paginasi
function renderPagination(totalItems, totalPages) {
  const infoEl = document.getElementById('paginationInfo');
  const btnsEl = document.getElementById('paginationButtons');

  const startShow = totalItems === 0 ? 0 : (currentPage - 1) * pageSize + 1;
  const endShow = Math.min(totalItems, currentPage * pageSize);
  const monthLabel = MONTH_NAMES[currentMonth] || currentMonth;

  if (infoEl) {
    infoEl.innerHTML = `Menampilkan <span class="font-bold text-[#0b1c30]">${startShow} - ${endShow}</span> dari <span class="font-bold text-[#0b1c30]">${totalItems}</span> nota transaksi bulan ${monthLabel}`;
  }

  if (!btnsEl) return;

  let html = `
    <button class="h-9 px-3 rounded-[0.25rem] text-[#76777d] hover:text-[#0b1c30] hover:bg-[#eff4ff] transition-colors disabled:opacity-40" 
            onclick="changePage(${currentPage - 1})" ${currentPage <= 1 ? 'disabled' : ''}>
      <span class="material-symbols-outlined text-[18px]">chevron_left</span>
    </button>
  `;

  // Dynamic Pagination items
  for (let p = 1; p <= totalPages; p++) {
    if (totalPages > 6 && p > 3 && p < totalPages) {
      if (p === 4) html += `<span class="px-1 text-[#76777d]">...</span>`;
      continue;
    }
    const isActive = p === currentPage;
    const cls = isActive
      ? 'h-9 w-9 rounded-[0.25rem] bg-[#000000] text-[#ffffff] font-bold shadow-sm'
      : 'h-9 w-9 rounded-[0.25rem] text-[#0b1c30] hover:bg-[#eff4ff] transition-colors';
    html += `<button class="${cls}" onclick="changePage(${p})">${p}</button>`;
  }

  html += `
    <button class="h-9 px-3 rounded-[0.25rem] text-[#76777d] hover:text-[#0b1c30] hover:bg-[#eff4ff] transition-colors disabled:opacity-40" 
            onclick="changePage(${currentPage + 1})" ${currentPage >= totalPages ? 'disabled' : ''}>
      <span class="material-symbols-outlined text-[18px]">chevron_right</span>
    </button>
  `;

  btnsEl.innerHTML = html;
}

function changePage(page) {
  currentPage = page;
  applyFiltersAndRender();
}

// ==========================================
// 4. MODAL DETAIL NOTA
// ==========================================
function openReceiptModal(notaId) {
  const allInMonth = TRANSACTIONS_DATA[currentMonth] || [];
  let tx = allInMonth.find(t => t.id === notaId);
  
  // Cari di seluruh bulan jika tidak ketemu di bulan aktif
  if (!tx) {
    for (const m of Object.keys(TRANSACTIONS_DATA)) {
      tx = TRANSACTIONS_DATA[m].find(t => t.id === notaId);
      if (tx) break;
    }
  }

  if (!tx) return;
  currentModalReceiptId = tx.id;

  // Isi data modal
  document.getElementById('modalNotaId').textContent = tx.id;
  
  const badge = document.getElementById('modalStatusBadge');
  if (badge) {
    if (tx.status === 'LUNAS') {
      badge.textContent = 'Lunas';
      badge.className = 'px-2 py-0.5 rounded bg-[#e5eeff] text-[#0b1c30]  text-[11px] leading-[14px] tracking-[0.04em] font-bold uppercase';
    } else {
      badge.textContent = 'DP / Tempo';
      badge.className = 'px-2 py-0.5 rounded bg-[#ffdbce] text-[#370e00]  text-[11px] leading-[14px] tracking-[0.04em] font-bold uppercase';
    }
  }

  document.getElementById('modalCustomer').textContent = tx.customer;
  document.getElementById('modalCustomerProject').textContent = tx.project || '-';
  
  const dateFormatted = formatDateDisplay(tx.date, null);
  document.getElementById('modalDateTime').textContent = dateFormatted.replace(' • ', '');
  document.getElementById('modalTimeSub').textContent = `${tx.time} WIB`;
  document.getElementById('modalCashier').textContent = tx.cashier;

  // Delivery details
  const deliveryTitle = document.getElementById('modalDeliveryTitle');
  const deliveryDesc = document.getElementById('modalDeliveryDesc');
  if (deliveryTitle) deliveryTitle.textContent = `Surat Jalan ${tx.deliveryNote}`;
  if (deliveryDesc) deliveryDesc.textContent = tx.deliveryLocation;

  // Breakdown Items
  const itemsTbody = document.getElementById('modalItemsTableBody');
  if (itemsTbody) {
    itemsTbody.innerHTML = tx.items.map((item, idx) => {
      const bg = idx % 2 === 0 ? 'bg-[#ffffff]' : 'bg-[#eff4ff]/30';
      return `
        <tr class="${bg}">
          <td class="py-2.5 px-3">
            <div class="text-[15px] leading-[20px] font-semibold text-[#0b1c30]">${item.name}</div>
            <div class="text-[11px] leading-[14px] tracking-[0.04em] font-bold text-[#76777d]">SKU: ${item.sku} • ${item.unit}</div>
          </td>
          <td class="py-2.5 px-3 text-center font-bold">${item.qty} ${item.unit}</td>
          <td class="py-2.5 px-3 text-right text-[#76777d]">${formatRp(item.price)}</td>
          <td class="py-2.5 px-3 text-right font-bold text-[#0b1c30]">${formatRp(item.subtotal)}</td>
        </tr>
      `;
    }).join('');
  }

  // Summary Finansial
  document.getElementById('modalSubtotal').textContent = formatRp(tx.total);
  document.getElementById('modalGrandTotal').textContent = formatRp(tx.total);
  document.getElementById('modalDpAmount').textContent = formatRp(tx.dpPaid);
  document.getElementById('modalRemainingDebt').textContent = formatRp(tx.remaining);

  // Tombol Pelunasan
  const payBtn = document.getElementById('modalActionPay');
  const payBtnText = document.getElementById('modalPayButtonText');
  if (payBtn) {
    if (tx.status === 'LUNAS') {
      payBtn.classList.add('opacity-50', 'pointer-events-none');
      if (payBtnText) payBtnText.textContent = 'Sudah Lunas';
    } else {
      payBtn.classList.remove('opacity-50', 'pointer-events-none');
      if (payBtnText) payBtnText.textContent = `Lunaskan Sekarang (${formatRp(tx.remaining)})`;
    }
  }

  // Buka Modal
  const modal = document.getElementById('receiptModal');
  if (modal) modal.classList.remove('hidden');
}

function closeReceiptModal() {
  const modal = document.getElementById('receiptModal');
  if (modal) modal.classList.add('hidden');
  currentModalReceiptId = null;
}

function handleModalBackdropClick(e) {
  if (e.target.id === 'receiptModal') {
    closeReceiptModal();
  }
}

// Lunaskan Nota Langsung (Interaktif)
function settleCurrentModalPayment() {
  if (!currentModalReceiptId) return;
  
  const rawList = TRANSACTIONS_DATA[currentMonth] || [];
  const target = rawList.find(t => t.id === currentModalReceiptId);
  if (target) {
    target.status = 'LUNAS';
    target.dpPaid = target.total;
    target.remaining = 0;
    
    // Perbarui modal yang sedang terbuka
    openReceiptModal(currentModalReceiptId);
    // Render ulang tabel
    applyFiltersAndRender();
  }
}

// Cetak Ulang Struk
function printSingleReceipt() {
  window.print();
}

// ==========================================
// 5. EKSPOR CSV / EXCEL
// ==========================================
function handleExport() {
  const data = getFilteredData();
  if (data.length === 0) {
    alert('Tidak ada data nota untuk diekspor!');
    return;
  }

  const headers = ['No. Nota', 'Tanggal', 'Jam', 'Pelanggan/Mandor', 'Proyek', 'Status', 'Total Nominal', 'DP Terbayar', 'Sisa Tempo', 'Kasir', 'Surat Jalan'];
  
  const csvRows = [headers.join(',')];

  data.forEach(item => {
    const row = [
      `"${item.id}"`,
      `"${item.date}"`,
      `"${item.time}"`,
      `"${(item.customer || '').replace(/"/g, '""')}"`,
      `"${(item.project || '').replace(/"/g, '""')}"`,
      `"${item.status}"`,
      item.total,
      item.dpPaid,
      item.remaining,
      `"${(item.cashier || '').replace(/"/g, '""')}"`,
      `"${(item.deliveryNote || '').replace(/"/g, '""')}"`
    ];
    csvRows.push(row.join(','));
  });

  const csvString = csvRows.join('\r\n');
  const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.setAttribute('href', url);
  link.setAttribute('download', `Riwayat_Nota_${currentMonth}_Export.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

// ==========================================
// 6. MODAL CETAK REKAP BULANAN A4
// ==========================================
function openMonthlyReportModal() {
  const data = TRANSACTIONS_DATA[currentMonth] || [];
  const monthTitle = MONTH_NAMES[currentMonth] || currentMonth;

  document.getElementById('printReportTitleMonth').textContent = monthTitle.toUpperCase();
  document.getElementById('printGeneratedDate').textContent = `Dicetak: ${new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}`;

  const totalTx = data.length;
  const totalRevenue = data.reduce((sum, d) => sum + d.total, 0);
  const totalPaid = data.reduce((sum, d) => sum + d.dpPaid, 0);
  const totalRemaining = data.reduce((sum, d) => sum + d.remaining, 0);

  const lunasCount = data.filter(d => d.status === 'LUNAS').length;
  const dpCount = data.filter(d => d.status === 'DP').length;
  const lunasPct = totalTx > 0 ? ((lunasCount / totalTx) * 100).toFixed(1) : '100.0';

  document.getElementById('printTotalTx').textContent = `${totalTx} Nota`;
  document.getElementById('printTotalRevenue').textContent = formatRp(totalRevenue);
  document.getElementById('printTotalPaid').textContent = formatRp(totalPaid);
  document.getElementById('printTotalRemaining').textContent = formatRp(totalRemaining);
  document.getElementById('printStatusBreakdown').textContent = `Komposisi: ${lunasCount} Nota Lunas (${lunasPct}%) • ${dpCount} Nota DP/Tempo (${(100 - parseFloat(lunasPct)).toFixed(1)}%)`;

  // Render Table Rekap Ringkas A4
  const tbody = document.getElementById('printReportTableBody');
  if (tbody) {
    tbody.innerHTML = data.map((d, index) => {
      return `
        <tr class="${index % 2 === 1 ? 'bg-slate-50' : 'bg-white'}">
          <td class="py-1.5 px-2.5 text-center font-mono text-slate-500">${index + 1}</td>
          <td class="py-1.5 px-2.5 font-bold font-mono text-slate-800">${d.id}</td>
          <td class="py-1.5 px-2.5 text-slate-600">${d.date}</td>
          <td class="py-1.5 px-2.5 text-slate-800">
            <div class="font-medium">${d.customer}</div>
            <div class="text-[10px] text-slate-400">${d.project || '-'}</div>
          </td>
          <td class="py-1.5 px-2.5">
            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold ${d.status === 'LUNAS' ? 'bg-emerald-100 text-emerald-800' : 'bg-orange-100 text-orange-800'}">
              ${d.status}
            </span>
          </td>
          <td class="py-1.5 px-2.5 text-right font-mono font-bold text-slate-900">${formatRp(d.total)}</td>
          <td class="py-1.5 px-2.5 text-right font-mono ${d.remaining > 0 ? 'text-orange-600 font-bold' : 'text-slate-400'}">${formatRp(d.remaining)}</td>
        </tr>
      `;
    }).join('');
  }

  const modal = document.getElementById('monthlyReportModal');
  if (modal) modal.classList.remove('hidden');
}

function closeMonthlyReportModal(e) {
  if (e && e.target && e.target.id !== 'monthlyReportModal' && e.type === 'click') return;
  const modal = document.getElementById('monthlyReportModal');
  if (modal) modal.classList.add('hidden');
}

// Inisialisasi awal saat halaman termuat
document.addEventListener('DOMContentLoaded', () => {
  switchMonthTab('2026-05');
});

// Fungsi dipanggil dari atribut onclick/oninput di HTML, jadi harus global
Object.assign(window, { formatRp, switchMonthTab, handleMonthSelectChange, navigateMonth, updateMonthTabsUI, setStatusFilter, updateStatusButtonsUI, handleSearchInput, clearSearch, handleDateRangeFilter, handlePageSizeChange, isDateInRange, getFilteredData, updateFilterCounts, applyFiltersAndRender, formatDateDisplay, renderFooterSummary, renderPagination, changePage, openReceiptModal, closeReceiptModal, handleModalBackdropClick, settleCurrentModalPayment, printSingleReceipt, handleExport, openMonthlyReportModal, closeMonthlyReportModal });