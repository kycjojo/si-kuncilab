# ALUR PASSING KUNCI MENGGUNAKAN QR

## Latar Belakang Fitur Passing Kunci

Dalam operasional sehari-hari laboratorium sekolah, seringkali terjadi pergantian jam pelajaran yang berurutan di laboratorium yang sama. Kelas pertama menggunakan laboratorium pada jam pertama, dilanjutkan dengan kelas kedua pada jam berikutnya. Pada skenario seperti ini, jika setiap kelas harus mengembalikan kunci ke pengawas dan meminjam lagi dari awal, maka akan terjadi antrean panjang di meja pengawas yang menyebabkan waktu pembelajaran terbuang percuma.

Untuk mengatasi inefisiensi tersebut, sistem Si Kunci Lab menyediakan fitur **Pass Kunci Langsung** (Direct Key Transfer). Fitur ini memungkinkan kunci laboratorium berpindah langsung dari siswa kelas sebelumnya (Siswa A) kepada siswa kelas berikutnya (Siswa B) tanpa perlu melalui meja pengawas secara fisik. Proses transfer ini diamankan menggunakan teknologi QR Code yang berlaku sekali pakai dan memiliki batas waktu kedaluwarsa.

## Tahapan Alur Passing Kunci

### Pengisian Checklist oleh Siswa A

Siswa A sebagai pemegang kunci yang akan mengakhiri sesinya tetap wajib menyelesaikan checklist kondisi ruangan dan mengunggah foto bukti laboratorium. Sistem tidak akan mengaktifkan tombol "Pass Kunci" sebelum kewajiban checklist ini terpenuhi. Hal ini memastikan bahwa pertanggungjawaban terhadap kondisi ruangan tetap berjalan meskipun kunci tidak dikembalikan ke pengawas.

### Pembangkitan Kode QR

Setelah checklist selesai diisi, Siswa A menekan tombol "Pass Kunci" yang terdapat pada dasbor. Sistem akan melakukan serangkaian validasi, antara lain memastikan bahwa Siswa A benar-benar pemegang peminjaman aktif yang bersangkutan dan bahwa status peminjaman masih dalam tahap yang memperbolehkan transfer.

Apabila validasi berhasil, sistem akan membangkitkan Kode QR unik yang bersifat:

1.  **Single-Use**: Kode QR hanya dapat digunakan satu kali. Setelah dipindai dan diproses, kode tersebut tidak dapat digunakan lagi.
2.  **Time-Limited**: Kode QR memiliki masa berlaku terbatas selama lima menit. Apabila tidak digunakan dalam jangka waktu tersebut, kode akan kedaluwarsa dan tidak dapat digunakan.
3.  **Token Dinamis**: Setiap kali tombol "Pass Kunci" ditekan, sistem akan membangkitkan token baru dan menonaktifkan token-token sebelumnya yang masih berstatus belum digunakan.

Kode QR ini ditampilkan pada layar perangkat Siswa A. Halaman penampil QR dilengkapi dengan hitung mundur yang menunjukkan sisa waktu berlaku kode. Ketika waktu tersisa mencapai enam puluh detik, tampilan akan berdenyut sebagai peringatan visual. Setelah kode kedaluwarsa, sistem secara otomatis membangkitkan kode baru tanpa perlu intervensi manual dari Siswa A.

### Pemindaian oleh Siswa B

Siswa B membuka aplikasi Si Kunci Lab pada perangkatnya dan memilih menu **"Ambil Kunci"** yang tersedia di dasbor. Menu ini akan mengaktifkan kamera belakang perangkat dan menampilkan antarmuka pemindai QR.

Siswa B kemudian mengarahkan kamera ke Kode QR yang ditampilkan pada layar perangkat Siswa A. Sistem pemindai menggunakan library jsQR untuk membaca dan mendekode kode QR secara real-time dari tangkapan kamera. Tidak diperlukan penekanan tombol pemindai tambahan; sistem secara otomatis mendeteksi dan memproses kode QR yang teridentifikasi.

Antarmuka pemindai dilengkapi dengan garis pemindaian animasi dan bingkai sudut yang membantu Siswa B mengarahkan kamera dengan tepat. Ketika kode QR berhasil terbaca, sistem akan menampilkan indikator keberhasilan dan memproses transaksi secara otomatis.

### Validasi dan Eksekusi Transfer

Setelah kode QR berhasil dibaca oleh perangkat Siswa B, token hasil pemindaian dikirimkan ke server untuk divalidasi. Server melakukan pemeriksaan sebagai berikut:

1.  **Keberadaan Token**: Memastikan token yang dikirimkan benar-benar terdaftar di dalam database.
2.  **Status Token**: Memastikan token masih berstatus "belum digunakan" (unused) dan belum kedaluwarsa.
3.  **Pemilik Token**: Memastikan Siswa B bukanlah pemilik token yang sama (tidak dapat memindai kode QR milik sendiri).
4.  **Status Peminjaman Siswa B**: Memastikan Siswa B tidak memiliki peminjaman aktif lainnya.

Apabila seluruh validasi terpenuhi, server akan mengeksekusi transaksi transfer di dalam sebuah database transaction untuk menjamin keutuhan data. Adapun langkah-langkah yang dieksekusi dalam transaksi tersebut adalah:

1.  **Menutup peminjaman Siswa A**: Status peminjaman Siswa A diubah menjadi "Selesai" dan waktu selesai aktual dicatat.
2.  **Membuka peminjaman baru untuk Siswa B**: Sistem membuat data peminjaman baru atas nama Siswa B dengan detail laboratorium yang sama seperti peminjaman Siswa A. Status peminjaman langsung ditetapkan sebagai "Berlangsung" dan tujuan peminjaman dicatat sebagai "Pass kunci dari [nama Siswa A]".
3.  **Mencatat serah terima**: Sistem mencatat bahwa penyerahan kunci kepada Siswa B dilakukan oleh Siswa A, lengkap dengan waktu serah terima.
4.  **Menandai token telah digunakan**: Token QR ditandai sebagai "used" dengan mencatat waktu penggunaan dan identitas Siswa B.

Seluruh rangkaian langkah di atas terjadi secara atomik dalam satu transaksi basis data. Apabila salah satu langkah gagal, maka seluruh transaksi akan dibatalkan (rollback) untuk mencegah inkonsistensi data.

## Keamanan dan Akuntabilitas

Meskipun kunci fisik berpindah tangan tanpa melalui pengawas, sistem tetap menjaga akuntabilitas melalui beberapa mekanisme:

1.  **Rekam Jejak Digital**: Setiap transfer dicatat lengkap dengan identitas penyerah (Siswa A), penerima (Siswa B), waktu transfer, dan peminjaman terkait. Data ini tersimpan sebagai log transfer yang dapat diakses oleh pengawas kapan saja.
2.  **Checklist Wajib**: Kewajiban checklist dan foto sebelum transfer memastikan bahwa kondisi ruangan saat serah terima tercatat dengan baik.
3.  **Log Transfer di Dasbor Pengawas**: Pengawas dapat melihat riwayat transfer kunci pada halaman detail peminjaman. Informasi yang ditampilkan mencakup nama penyerah, nama penerima, dan waktu transfer dalam format linimasa.
