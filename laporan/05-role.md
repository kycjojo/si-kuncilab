# PENJELASAN ROLE PENGGUNA

## Konsep Role-Based Access Control

Sistem Si Kunci Lab menerapkan konsep Role-Based Access Control (RBAC) dalam pengelolaan akses pengguna. Setiap pengguna yang terdaftar dalam sistem memiliki satu peran (role) yang menentukan fitur dan data apa saja yang dapat diaksesnya. Terdapat tiga peran utama dalam sistem, yaitu Siswa, Pengawas, dan Administrator.

## Role Siswa (Murid)

### Hak Akses

Role siswa merupakan peran dengan lingkup akses paling terbatas. Siswa hanya dapat mengakses fitur-fitur yang berkaitan dengan aktivitas peminjaman secara personal. Hak akses siswa meliputi:

1.  Mengajukan permohonan peminjaman laboratorium baru
2.  Melihat daftar peminjaman miliknya sendiri beserta status terkini
3.  Mengisi checklist pengembalian laboratorium
4.  Mengunggah foto bukti kondisi ruangan
5.  Melakukan passing kunci langsung kepada siswa lain
6.  Mengambil (memindai QR) kunci yang dioper oleh siswa lain
7.  Melihat riwayat peminjaman yang sudah selesai

### Batasan

Siswa tidak dapat mengakses data peminjaman milik siswa lain, tidak dapat menyetujui atau menolak permohonan peminjaman, dan tidak dapat mengelola data master seperti data laboratorium atau data pengguna. Siswa yang memiliki total poin pelanggaran lima atau lebih secara otomatis diblokir oleh sistem dan tidak dapat melakukan peminjaman baru sampai administrator mereset poinnya.

## Role Pengawas

### Hak Akses

Role pengawas merupakan peran operasional yang memiliki kewenangan untuk mengelola siklus peminjaman sehari-hari. Pengawas bertanggung jawab atas validasi dan eksekusi serah terima kunci. Hak akses pengawas meliputi:

1.  Melihat seluruh daftar permohonan peminjaman yang masuk
2.  Menyetujui atau menolak permohonan peminjaman
3.  Mencatat serah terima kunci (Serah Kunci dan Terima Kunci)
4.  Memeriksa bukti checklist dan foto yang diunggah siswa
5.  Melihat detail peminjaman termasuk log transfer kunci
6.  Memberikan poin pelanggaran kepada siswa

### Batasan

Pengawas tidak dapat mengelola data master seperti menambah atau menghapus data laboratorium dan data pengguna. Pengawas juga tidak dapat mereset poin pelanggaran siswa atau membuka blokir akun siswa.

## Role Administrator (Admin)

### Hak Akses

Role administrator merupakan peran tertinggi dalam sistem dengan kewenangan penuh atas seluruh data dan konfigurasi. Administrator bertanggung jawab atas pengelolaan data master dan pengaturan sistem secara keseluruhan. Hak akses administrator meliputi:

1.  Mengelola data pengguna (menambah, mengedit, menghapus)
2.  Mengelola data laboratorium (menambah, mengedit, menghapus)
3.  Mereset poin pelanggaran siswa dan membuka blokir akun
4.  Melihat seluruh data peminjaman tanpa terkecuali
5.  Mencetak laporan rekapitulasi peminjaman, keterlambatan, dan pelanggaran
6.  Mengakses seluruh fitur yang dimiliki oleh pengawas

### Batasan

Administrator tidak memiliki batasan akses yang signifikan dalam sistem. Seluruh data dan fitur dapat diakses oleh administrator. Namun dalam praktiknya, administrator tidak terlibat dalam operasional harian seperti verifikasi peminjaman atau serah terima kunci, karena tugas tersebut merupakan wewenang pengawas.

## Tabel Ringkasan Peran

| Fitur / Kewenangan | Siswa | Pengawas | Admin |
|---|---|---|---|
| Mengajukan peminjaman | ✓ | - | - |
| Menyetujui / menolak peminjaman | - | ✓ | ✓ |
| Serah terima kunci | - | ✓ | ✓ |
| Mengisi checklist | ✓ | - | - |
| Melihat riwayat sendiri | ✓ | ✓ | ✓ |
| Melihat semua peminjaman | - | ✓ | ✓ |
| Poin pelanggaran | - | ✓ | ✓ |
| Reset poin / buka blokir | - | - | ✓ |
| Kelola data pengguna | - | - | ✓ |
| Kelola data laboratorium | - | - | ✓ |
| Cetak laporan | - | - | ✓ |
