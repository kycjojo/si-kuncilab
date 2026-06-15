# ALUR PEMINJAMAN KUNCI

\section*{Mekanisme Peminjaman Kunci}

Peminjaman kunci laboratorium merupakan fitur utama dalam sistem Si Kunci Lab. Alur peminjaman dirancang untuk memastikan setiap transaksi peminjaman tercatat dengan baik dan dapat dilacak. Proses peminjaman melibatkan tiga aktor utama, yaitu mahasiswa sebagai peminjam, pengawas sebagai pengelola, dan sistem sebagai mediator.

\section*{Aktor dalam Sistem}

Terdapat tiga peran pengguna dalam sistem Si Kunci Lab, yaitu:

\begin{enumerate}
    \item \textbf{Admin}: Memiliki akses penuh terhadap sistem, termasuk manajemen pengguna, manajemen laboratorium, dan pembuatan laporan.
    \item \textbf{Pengawas}: Bertanggung jawab menyetujui atau menolak permintaan peminjaman, menyerahkan kunci, dan menerima pengembalian kunci.
    \item \textbf{Murid}: Mengajukan permintaan peminjaman, mengisi checklist pengembalian, dan melakukan passing kunci.
\end{enumerate}

\section*{Status Peminjaman}

Setiap peminjaman dalam sistem memiliki status yang merepresentasikan tahapan proses. Status-status tersebut adalah:

\begin{enumerate}
    \item \textbf{Menunggu}: Permintaan peminjaman telah diajukan oleh mahasiswa dan menunggu persetujuan pengawas.
    \item \textbf{Disetujui}: Pengawas telah menyetujui permintaan peminjaman dan kunci siap diserahkan.
    \item \textbf{Berlansgung}: Kunci telah diserahkan kepada mahasiswa dan peminjaman sedang berlangsung.
    \item \textbf{Checklist Diisi}: Mahasiswa telah mengisi checklist pengembalian sebagai syarat pengembalian kunci.
    \item \textbf{Selesai}: Kunci telah dikembalikan dan proses peminjaman dinyatakan selesai.
    \item \textbf{Terlambat}: Peminjaman melebihi batas waktu yang telah ditentukan.
\end{enumerate}

\section*{Prosedur Peminjaman}

Prosedur peminjaman kunci laboratorium dalam sistem Si Kunci Lab adalah sebagai berikut:

\subsection*{Pengajuan Permintaan}

Mahasiswa mengajukan permintaan peminjaman melalui dashboard sistem. Mahasiswa memilih laboratorium yang akan digunakan, menentukan tanggal peminjaman, jam mulai, jam selesai, serta tujuan peminjaman. Mahasiswa juga dapat menggunakan fitur \textit{Quick Borrow} yang tersedia di dashboard untuk mempercepat proses pengajuan.

\subsection*{Persetujuan Pengawas}

Pengawas menerima notifikasi permintaan peminjaman yang masuk. Pengawas dapat menyetujui atau menolak permintaan tersebut. Jika disetujui, status peminjaman berubah menjadi "Disetujui" dan pengawas siap menyerahkan kunci kepada mahasiswa.

\subsection*{Penyerahan Kunci}

Setelah permintaan disetujui, pengawas menyerahkan kunci laboratorium kepada mahasiswa secara langsung. Pada tahap ini, sistem mencatat penyerahan kunci beserta waktu serah. Status peminjaman berubah menjadi "Berlangsung".

\section*{Quick Borrow}

Fitur \textit{Quick Borrow} memungkinkan mahasiswa untuk melakukan peminjaman dengan cepat tanpa perlu mengisi formulir panjang. Mahasiswa cukup memilih laboratorium yang tersedia dan durasi peminjaman (sesi), dan sistem akan secara otomatis mengajukan permintaan peminjaman dengan tujuan default "Praktikum". Fitur ini hanya tersedia jika mahasiswa tidak sedang memiliki peminjaman aktif dan tidak dalam status diblokir.
