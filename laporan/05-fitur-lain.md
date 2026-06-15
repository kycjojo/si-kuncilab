# FITUR-FITUR LAINNYA

\section*{Sistem Pelanggaran}

Sistem dilengkapi dengan manajemen pelanggaran untuk meningkatkan kedisiplinan pengguna. Setiap pelanggaran yang dilakukan oleh mahasiswa dicatat dan diberikan poin pelanggaran (\textit{poin\_pelanggaran}). Poin ini dapat diberikan oleh pengawas atau admin berdasarkan jenis pelanggaran yang dilakukan.

\subsection*{Pemblokiran Otomatis}

Ketika total poin pelanggaran seorang mahasiswa mencapai 5 atau lebih, sistem secara otomatis memblokir mahasiswa tersebut. Pemblokiran diperiksa pada saat login melalui \texttt{AuthController} dan pada setiap permintaan murid melalui middleware \texttt{CheckBlocked}. Mahasiswa yang diblokir tidak dapat mengajukan peminjaman baru atau menggunakan fitur lainnya. Admin dapat mereset poin pelanggaran dan membuka blokir melalui halaman manajemen pengguna.

\section*{Manajemen Pengguna}

Admin memiliki akses penuh untuk mengelola pengguna dalam sistem. Fitur manajemen pengguna meliputi:

\begin{enumerate}
    \item Membuat, mengedit, dan menghapus pengguna.
    \item Mengatur peran pengguna (Admin, Pengawas, Murid).
    \item Mereset poin pelanggaran dan membuka blokir pengguna.
    \item Melihat riwayat peminjaman setiap pengguna.
\end{enumerate}

\section*{Manajemen Laboratorium}

Admin dapat mengelola data laboratorium yang tersedia dalam sistem. Setiap laboratorium memiliki identitas berupa nama, kode, lokasi, dan kapasitas. Data laboratorium digunakan sebagai referensi dalam proses peminjaman kunci.

\section*{Laporan dan Monitoring}

Sistem menyediakan fitur laporan untuk kebutuhan monitoring dan evaluasi. Laporan dapat diakses oleh admin dan pengawas. Beberapa jenis laporan yang tersedia adalah:

\begin{enumerate}
    \item \textbf{Laporan Peminjaman}: Menampilkan riwayat peminjaman berdasarkan periode waktu, laboratorium, atau pengguna.
    \item \textbf{Laporan Pelanggaran}: Menampilkan data pelanggaran dan poin yang telah diberikan.
    \item \textbf{Laporan Pengguna}: Menampilkan data pengguna aktif dan statusnya.
    \item \textbf{Laporan Cetak}: Setiap laporan memiliki varian cetak (\textit{print}) dengan format A4 landscape yang siap dicetak.
\end{enumerate}

\section*{Manajemen Sesi}

Sistem menggunakan sesi berbasis file (\texttt{file}) di lingkungan pengembangan dan sesi berbasis basis data (\texttt{database}) di lingkungan produksi. Perbedaan ini diatur melalui file konfigurasi \texttt{.env}. Sistem juga menggunakan antrian sinkron (\texttt{sync}) sehingga tidak memerlukan \textit{worker} terpisah.

\section*{Keamanan Sistem}

Sistem menerapkan beberapa lapisan keamanan untuk melindungi data dan akses pengguna:

\begin{enumerate}
    \item \textbf{Authentication}: Setiap pengguna harus login untuk mengakses sistem.
    \item \textbf{Authorization}: Akses dibatasi berdasarkan peran pengguna melalui middleware \texttt{RoleMiddleware}.
    \item \textbf{Blokir Murid}: Middleware \texttt{CheckBlocked} memblokir murid dengan poin pelanggaran tinggi.
    \item \textbf{Validasi Data}: Setiap input divalidasi sebelum diproses.
\end{enumerate}
