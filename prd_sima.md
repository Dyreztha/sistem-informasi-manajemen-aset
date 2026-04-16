# PRD – Sistem Informasi Manajemen Aset (SIMA)

Versi: 1.0  
Tanggal: 21 Januari 2026  
Status: Draft

---

## 1. Pendahuluan

### 1.1 Latar Belakang
Pengelolaan aset perusahaan/instansi saat ini masih menggunakan spreadsheet manual yang rentan kesalahan. Hal ini menyebabkan kesulitan dalam melacak keberadaan aset, menghitung penyusutan nilai (depresiasi), menjadwalkan pemeliharaan, serta proses audit (stock opname) yang memakan waktu lama.

### 1.2 Tujuan
1. **Sentralisasi Data**: Mencatat seluruh aset tetap (IT, Furniture, Kendaraan, Mesin) dalam satu database.
2. **Tracking Lokasi & Kepemilikan**: Mengetahui posisi aset dan siapa penanggung jawabnya secara real-time.
3. **Manajemen Siklus Hidup**: Mengelola aset mulai dari pengadaan, pemeliharaan, hingga penghapusan (disposal).
4. **Digitalisasi Audit**: Memudahkan proses Stock Opname menggunakan QR Code.

---

## 2. Target Pengguna (User Persona)

| Peran              | Deskripsi                                | Hak Akses                                              |
|-------------------|-------------------------------------------|--------------------------------------------------------|
| Admin Aset        | Penanggung jawab utama inventaris         | Full Access (CRUD), Generate QR, Approval Mutasi       |
| Pimpinan/Manajemen| Pemantau nilai kekayaan aset              | View Dashboard, View Laporan Keuangan Aset             |
| Staff/Peminjam   | Pengguna yang menggunakan aset            | Request Peminjaman, Lapor Kerusakan                    |
| Auditor           | Petugas pemeriksa fisik aset              | Akses Menu Stock Opname (Scan QR)                      |

---

## 3. Fitur Utama & Spesifikasi Fungsional

### 3.1 Dashboard Aset
Menampilkan ringkasan status aset secara visual.

- **Total Nilai Aset**: Akumulasi nilai perolehan dan nilai buku saat ini.
- **Status Kondisi**: Grafik aset (Baik, Rusak Ringan, Rusak Berat, Hilang).
- **Maintenance Alert**: Daftar aset yang jatuh tempo untuk servis/perawatan.
- **Aset Low Stock**: Peringatan untuk barang habis pakai (jika ada modul consumable).

### 3.2 Modul Master Aset (Registry)
Pencatatan detail aset.

- **Input Data**: Nama, Kategori, Merk, Tipe, Nomor Seri, Vendor, Tanggal Beli, Harga Perolehan.
- **Generate QR Code**: Sistem otomatis membuat label QR unik per aset.
- **Perhitungan Depresiasi**: Kalkulasi otomatis penyusutan nilai (Metode Garis Lurus / Double Declining) per tahun/bulan.
- **Upload Dokumen**: Upload faktur, kartu garansi, dan foto fisik aset.

### 3.3 Modul Sirkulasi (Check-in / Check-out)
Mengatur pergerakan aset.

- **Peminjaman / Penugasan**: Assign aset ke karyawan tertentu.
- **Mutasi Lokasi**: Memindahkan aset antar ruangan/cabang.
- **Pengembalian**: Mencatat pengembalian aset dari karyawan (misal: resign).
- **BAST Digital**: Generate Berita Acara Serah Terima otomatis saat serah terima.

### 3.4 Modul Pemeliharaan (Maintenance)

- **Jadwal Berkala**: Set jadwal servis (misal: AC per 3 bulan, Kendaraan per 5000 km).
- **Tiket Perbaikan**: Staff melaporkan aset rusak → Admin menindaklanjuti (Perbaiki / Ganti).
- **Riwayat Biaya**: Mencatat biaya perawatan untuk menghitung Total Cost of Ownership.

### 3.5 Modul Stock Opname (Audit)

- **Sesi Opname**: Membuat periode audit baru.
- **Scan Match**: Scan QR Code di lokasi menggunakan kamera HP / scanner.
- **Discrepancy Report**: Laporan selisih antara data sistem vs fisik:
  - Ditemukan
  - Tidak Ditemukan
  - Pindah Lokasi Tanpa Lapor

---

## 4. Alur Kerja (User Flow)

### 4.1 Registrasi Aset Baru
1. Admin menerima barang dari vendor.
2. Input data ke SIMA (Kategori, Harga, Tanggal Beli).
3. Sistem generate QR Code.
4. Admin mencetak stiker QR dan menempelkan di aset.
5. Status aset: **"Tersedia"** di **"Gudang Utama"**.

### 4.2 Peminjaman Aset (Contoh: Laptop Karyawan)
1. Staff mengajukan request laptop via sistem.
2. Manager menyetujui request.
3. Admin menyiapkan aset → Scan QR Aset → Pilih Nama Staff.
4. Sistem mencatat status: **"Digunakan" oleh "Staff A"**.
5. Sistem generate BAST Digital → Staff klik setuju.

### 4.3 Proses Stock Opname
1. Auditor membuka menu **"Stock Opname Ruang Server"**.
2. Auditor melakukan scan semua stiker QR di ruangan.
3. Sistem menandai **"Ada"** untuk setiap scan valid.
4. Di akhir sesi, sistem menampilkan daftar aset yang:
   - Tercatat di Ruang Server
   - Tetapi tidak terscan (indikasi hilang / pindah)

---

## 5. Kebutuhan Non-Fungsional

1. **Mobile Responsive**: Modul Scan QR harus berjalan lancar di browser mobile.
2. **History Log**: Audit trail lengkap (siapa mengubah lokasi, siapa mengedit harga).
3. **Notifikasi**: Email / WhatsApp reminder untuk jadwal maintenance H-3.
4. **Integrasi** (Opsional): API ke sistem Finance untuk sinkronisasi nilai depresiasi.

---

## 6. Mockup Interface (Konsep)

### 6.1 Dashboard

```
+-------------------------------------------------------+
| TOTAL ASET: Rp 1.5 Milyar  |  MAINTENANCE DUE: 5 Item |
+-------------------------------------------------------+
| KONDISI ASET:                                         |
| [=============       ] 70% Baik                       |
| [====                ] 20% Rusak Ringan               |
| [==                  ] 10% Rusak Berat                |
+-------------------------------------------------------+
```

### 6.2 Detail Aset (Form)

```
Kode Aset : [AST-2026-001]   [ GENERATE QR ]
Nama Barang: [Laptop Thinkpad X1]
Lokasi     : [Lantai 2 - R. Staff]
Penanggung Jawab: [Budi Santoso]

---------------------------------------------------------
Nilai Beli    : [Rp 25.000.000]
Tgl Beli      : [01/01/2025]
Nilai Saat Ini: [Rp 20.000.000] (Depresiasi 20%)
---------------------------------------------------------
[ RIWAYAT MUTASI ] [ RIWAYAT PERBAIKAN ] [ LAMPIRAN ]
```

### 6.3 Mobile Scan Mode (Stock Opname)

```
+-----------------------+
|  CAMERA VIEWFINDER    |
|        [  ]           |
|                       |
+-----------------------+
| Scanned: 15 / 50      |
| Last   : Kursi Kerja 04 |
| Status : Match [v]   |
+-----------------------+
```
