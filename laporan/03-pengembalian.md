# ALUR PENGEMBALIAN KUNCI

\section*{Mekanisme Pengembalian Kunci}

Pengembalian kunci laboratorium merupakan tahapan penting dalam siklus peminjaman. Proses pengembalian tidak hanya melibatkan pengembalian kunci secara fisik, tetapi juga pemeriksaan kondisi ruang laboratorium untuk memastikan kebersihan dan kelayakan penggunaan bagi peminjam selanjutnya.

\section*{Checklist Pengembalian}

Sebelum mengembalikan kunci, mahasiswa wajib mengisi checklist pengembalian melalui sistem. Checklist ini berfungsi sebagai laporan kondisi ruangan setelah digunakan. Terdapat lima aspek yang diperiksa dalam checklist pengembalian, yaitu:

\begin{enumerate}
    \item Kebersihan meja dan kursi di dalam ruangan.
    \item Kebersihan lantai ruangan.
    \item Kondisi peralatan dan fasilitas laboratorium.
    \item Kondisi papan tulis yang telah dibersihkan.
    \item Kondisi jendela dan ventilasi ruangan.
\end{enumerate}

Setiap aspek dinilai dalam bentuk boolean (ya/tidak). Selain itu, mahasiswa juga diwajibkan mengunggah foto kondisi ruangan sebagai bukti visual. Foto disimpan di direktori \texttt{storage/app/public/checklist-photos/} dan disajikan melalui symlink \texttt{storage/}.

\section*{Prosedur Pengembalian}

Prosedur pengembalian kunci laboratorium adalah sebagai berikut:

\subsection*{Pengisian Checklist}

Mahasiswa yang sedang dalam masa peminjaman (status "Berlangsung") mengakses halaman checklist pengembalian. Mahasiswa mengisi lima field checklist dan mengunggah foto kondisi ruangan. Setelah checklist disimpan, status peminjaman berubah menjadi "Checklist Diisi".

\subsection*{Pemeriksaan oleh Pengawas}

Pengawas menerima notifikasi bahwa checklist telah diisi oleh mahasiswa. Pengawas memeriksa kebenaran checklist dan kondisi ruangan berdasarkan foto yang diunggah. Jika checklist sesuai, pengawas dapat menyelesaikan proses peminjaman.

\subsection*{Penyelesaian Peminjaman}

Setelah pengawas memverifikasi checklist, kunci diterima kembali oleh pengawas dan status peminjaman berubah menjadi "Selesai". Sistem mencatat waktu pengembalian aktual untuk keperluan monitoring.

\section*{Penanganan Keterlambatan}

Jika mahasiswa mengembalikan kunci melebihi batas waktu yang telah ditentukan (\texttt{jam\_selesai\_aktual > jam\_selesai\_rencana}), sistem akan menandai peminjaman sebagai "Terlambat". Keterlambatan ini dicatat dalam sistem sebagai pelanggaran yang dapat mempengaruhi poin pelanggaran mahasiswa.
