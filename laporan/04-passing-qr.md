# ALUR PASSING KUNCI MENGGUNAKAN QR

\section*{Konsep Passing Kunci}

Fitur \textit{Pass Kunci Langsung} memungkinkan mahasiswa yang sedang memegang kunci (status peminjaman "Berlangsung") untuk menyerahkan kunci secara langsung kepada mahasiswa lain tanpa harus melalui pengawas. Fitur ini dirancang untuk meningkatkan efisiensi dan fleksibilitas dalam penggunaan laboratorium.

\section*{Mekanisme QR Token}

Mekanisme passing kunci menggunakan QR code sebagai media pertukaran informasi. Sistem menghasilkan token unik yang dienkapsulasi dalam QR code. Token ini memiliki karakteristik sebagai berikut:

\begin{enumerate}
    \item \textbf{Single-use}: Token hanya dapat digunakan satu kali. Setelah digunakan, token tidak dapat digunakan kembali.
    \item \textbf{Time-limited}: Token memiliki masa berlaku terbatas, yaitu 5 menit sejak dibuat. Setelah kedaluwarsa, token tidak dapat digunakan dan harus dibuat ulang.
    \item \textbf{Unique}: Setiap token memiliki nilai acak sepanjang 48 karakter yang dihasilkan menggunakan fungsi \texttt{Str::random(48)}.
    \item \textbf{Auditable}: Setiap token mencatat peminjaman asal, pengguna asal, pengguna tujuan, dan waktu penggunaan.
\end{enumerate}

\section*{Prosedur Passing Kunci}

Prosedur passing kunci menggunakan QR code terdiri dari beberapa tahapan sebagai berikut:

\subsection*{Persiapan (Mahasiswa A)}

Mahasiswa A (peminjam awal) mengakses halaman \textit{Pass Kunci} dari peminjaman aktifnya. Sebelum dapat melakukan passing, mahasiswa A harus mengisi checklist pengembalian terlebih dahulu. Hal ini memastikan bahwa kondisi laboratorium telah terdokumentasi. Setelah checklist diisi, mahasiswa A menekan tombol "Generate QR" untuk menghasilkan token baru. Sistem akan membatalkan token sebelumnya yang masih aktif untuk mencegah kebingungan. QR code ditampilkan di layar mahasiswa A dengan batas waktu 5 menit.

\subsection*{Pemindaian QR (Mahasiswa B)}

Mahasiswa B membuka halaman \textit{Ambil Kunci} di dashboardnya. Halaman ini mengakses kamera perangkat untuk memindai QR code yang ditampilkan di layar mahasiswa A. Pemindaian menggunakan library \textit{jsQR} dari CDN yang membaca frame video secara real-time.

\subsection*{Redemption Token}

Setelah QR code berhasil dipindai, token yang terkandung dalam QR dikirim ke server melalui endpoint \texttt{/ambil-kunci/redeem}. Server melakukan validasi berikut:

\begin{enumerate}
    \item Token harus ada dalam basis data.
    \item Token harus berstatus "unused".
    \item Token belum kedaluwarsa (masih dalam 5 menit).
    \item Mahasiswa B bukan pemilik token yang sama dengan mahasiswa A.
    \item Mahasiswa B tidak sedang memiliki peminjaman aktif.
\end{enumerate}

\subsection*{Eksekusi Transaksi}

Jika validasi berhasil, server mengeksekusi transaksi dalam \textit{database transaction} untuk memastikan konsistensi data. Transaksi mencakup:

\begin{enumerate}
    \item Menutup peminjaman mahasiswa A dengan status "Selesai" dan mencatat waktu selesai aktual.
    \item Membuat peminjaman baru untuk mahasiswa B dengan status "Berlangsung", menyalin data laboratorium, tanggal, dan jam dari peminjaman A.
    \item Mencatat penyerahan kunci untuk peminjaman baru mahasiswa B.
    \item Memperbarui status token menjadi "used" dan mencatat pengguna serta peminjaman tujuan.
\end{enumerate}

\section*{Keamanan Transaksi}

Fitur passing kunci menggunakan mekanisme \textit{database transaction} untuk mencegah \textit{race condition}. Ketika dua pengguna mencoba memindai QR yang sama secara bersamaan, hanya pengguna pertama yang berhasil karena token akan berubah status menjadi "used" setelah transaksi pertama selesai. Transaksi kedua akan gagal karena token sudah tidak berstatus "unused".

Token yang kedaluwarsa tidak dihapus dari basis data, melainkan diubah statusnya menjadi "expired". Hal ini bertujuan untuk menjaga jejak audit (\textit{audit trail}) untuk keperluan investigasi jika diperlukan.

\section*{Tampilan pada Pengawas}

Pengawas dapat memantau riwayat passing kunci pada halaman detail peminjaman. Sistem menampilkan log transfer yang mencakup mahasiswa asal, mahasiswa tujuan, dan waktu serah terima. Informasi ini membantu pengawas dalam memonitor pergerakan kunci tanpa harus terlibat langsung dalam setiap transaksi.
