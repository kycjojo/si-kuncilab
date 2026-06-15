# FITUR-FITUR LAINNYA

## Sistem Poin dan Blokir

Sistem poin pelanggaran merupakan mekanisme sanksi digital yang diterapkan untuk menjaga kedisiplinan siswa dalam menggunakan laboratorium. Pengawas atau administrator dapat memberikan poin pelanggaran kepada siswa apabila terjadi pelanggaran tata tertib, seperti keterlambatan mengembalikan kunci, kelalaian membersihkan ruangan, atau kerusakan fasilitas laboratorium akibat kelalaian.

Setiap siswa memiliki akumulasi poin yang tercatat di dalam sistem. Apabila total poin mencapai angka lima atau lebih, maka sistem secara otomatis akan memblokir akun siswa yang bersangkutan. Siswa yang terblokir tidak dapat mengajukan peminjaman baru, mengisi checklist, atau melakukan transfer kunci. Pemblokiran diperiksa setiap kali siswa melakukan aksi di dalam sistem melalui middleware CheckBlocked, serta pada saat login melalui mekanisme otentikasi.

Pemblokiran hanya dapat dibuka oleh administrator melalui fitur manajemen pengguna. Administrator dapat mereset poin pelanggaran siswa kembali menjadi nol, yang secara otomatis akan menghapus status blokir pada akun tersebut.

## Manajemen Kunci oleh Pengawas

Pengawas memiliki panel khusus yang menampilkan seluruh pergerakan kunci laboratorium secara real-time. Panel ini menampilkan daftar peminjaman yang sedang berlangsung, peminjaman yang menunggu persetujuan, dan riwayat peminjaman yang telah selesai.

 Pada panel tersebut, pengawas dapat:

1.  Melihat daftar permohonan peminjaman yang masuk dan memprosesnya satu per satu
2.  Melakukan serah terima kunci yang mencatat waktu pengambilan dan pengembalian secara otomatis
3.  Memeriksa bukti checklist dan foto yang diunggah siswa sebelum menutup peminjaman
4.  Melihat log transfer kunci yang terjadi antar siswa tanpa melalui pengawas

Panel manajemen kunci ini dirancang sebagai pusat kendali operasional harian pengawas dalam mengelola seluruh aktivitas peminjaman laboratorium.

## Log Transfer Kunci

Setiap kali terjadi transfer kunci antar siswa melalui fitur Pass Kunci Langsung, sistem secara otomatis mencatat transaksi tersebut ke dalam tabel log transfer. Data yang dicatat meliputi:

1.  Identitas siswa yang menyerahkan kunci (Siswa A)
2.  Identitas siswa yang menerima kunci (Siswa B)
3.  Waktu transfer
4.  Token QR yang digunakan
5.  Peminjaman terkait (peminjaman lama milik Siswa A dan peminjaman baru milik Siswa B)

Log transfer ini ditampilkan pada halaman detail peminjaman di dasbor pengawas dalam format linimasa. Informasi ini memudahkan pengawas untuk melacak riwayat perpindahan kunci apabila terjadi masalah di kemudian hari.

## Laporan dan Rekapitulasi

Administrator memiliki akses ke fitur laporan yang memungkinkan pencetakan rekapitulasi data dalam berbagai format. Jenis laporan yang tersedia meliputi:

1.  **Laporan Riwayat Peminjaman**: Menampilkan seluruh transaksi peminjaman dalam rentang waktu tertentu beserta statusnya masing-masing.
2.  **Laporan Keterlambatan**: Menampilkan daftar peminjaman yang mengalami keterlambatan pengembalian disertai durasi keterlambatan.
3.  **Rekapitulasi Pelanggaran**: Menampilkan akumulasi poin pelanggaran siswa beserta detail pelanggaran yang telah dicatat.

Laporan-laporan tersebut tersedia dalam format cetak (landscape A4) yang siap diserahkan kepada pihak sekolah atau institusi terkait untuk keperluan evaluasi dan pengambilan keputusan.

## Dasbor Siswa

Dasbor siswa dirancang untuk memberikan informasi yang relevan dan ringkas dalam satu tampilan. Dasbor terdiri dari dua kolom utama:

1. **Kolom Kiri** menampilkan informasi peminjaman aktif siswa beserta tombol aksi yang sesuai dengan status peminjaman, seperti tombol "Pass Kunci" apabila checklist telah diisi atau tombol "Ambil Kunci" apabila siswa tidak memiliki peminjaman aktif.
2. **Kolom Kanan** menampilkan ringkasan statistik peminjaman siswa, seperti total peminjaman yang pernah dilakukan, jumlah peminjaman yang sedang berlangsung, jumlah keterlambatan, dan total poin pelanggaran.

Dasbor ini juga menampilkan riwayat peminjaman yang sudah selesai dalam bentuk tabel yang dapat diurutkan berdasarkan tanggal.
