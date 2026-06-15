<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Proyek — Si Kunci Lab</title>
    <style>
        @page {
            margin: 30mm 25mm 25mm 25mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000000;
            line-height: 1.5;
        }

        /* ── Cover ── */
        .cover {
            text-align: center;
            padding-top: 150px;
            height: 100vh;
            box-sizing: border-box;
        }
        .cover h1 {
            font-size: 22pt;
            font-weight: bold;
            margin: 0 0 15px;
            text-transform: uppercase;
            line-height: 1.3;
        }
        .cover .sub {
            font-size: 14pt;
            font-weight: normal;
            margin-bottom: 50px;
        }
        .cover .meta {
            position: absolute;
            bottom: 100px;
            width: 100%;
            text-align: center;
            font-size: 12pt;
            line-height: 1.5;
        }

        /* ── TOC ── */
        .toc { page-break-after: always; }
        .toc h2 {
            text-align: center;
            font-size: 14pt;
            text-transform: uppercase;
            border: none;
            margin-bottom: 30px;
        }
        .toc ul { list-style: none; padding: 0; margin: 0; }
        .toc li {
            padding: 4px 0;
            font-size: 12pt;
            display: flex;
        }
        .toc .num {
            width: 80px;
            display: inline-block;
            font-weight: bold;
        }
        .toc .sub-toc {
            padding-left: 30px;
        }
        .toc .num-sub {
            width: 50px;
            display: inline-block;
        }

        /* ── Typography ── */
        h2 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            margin: 40px 0 20px;
            page-break-after: avoid;
        }
        h3 {
            font-size: 12pt;
            font-weight: bold;
            margin: 25px 0 10px;
            page-break-after: avoid;
        }
        h4 {
            font-size: 12pt;
            font-weight: bold;
            font-style: italic;
            margin: 15px 0 5px;
            page-break-after: avoid;
        }

        p {
            margin: 10px 0;
            text-align: justify;
            text-indent: 1.25cm;
        }

        /* ── Lists ── */
        ul, ol {
            margin: 10px 0 10px 1.25cm;
            padding-left: 20px;
            text-align: justify;
        }
        li { margin-bottom: 5px; }
        ul li { list-style-type: disc; }

        /* ── Tables (Formal Style) ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11pt;
            page-break-inside: avoid;
        }
        th, td {
            padding: 8px 10px;
            border: 1px solid #000;
            text-align: left;
            vertical-align: top;
        }
        th {
            font-weight: bold;
            text-align: center;
        }

        /* ── Layout Helpers ── */
        .page-break { page-break-before: always; }

        .no-indent {
            text-indent: 0;
        }

        .flow {
            text-align: center;
            font-weight: bold;
            margin: 20px 0;
            font-style: italic;
        }

        .footer {
            position: fixed;
            bottom: -15px;
            left: 0px;
            right: 0px;
            height: 50px;
            font-size: 10pt;
            text-align: center;
        }

        .page-number:before {
            content: counter(page);
        }
    </style>
</head>
<body>

<!-- ═══════════════════ COVER ═══════════════════ -->
<div class="cover">
    <h1>LAPORAN PROYEK<br>SI KUNCI LAB</h1>
    <div class="sub">Sistem Informasi Peminjaman Kunci Laboratorium Terintegrasi<br>Berbasis Web</div>

    <div class="meta">
        Disusun Oleh:<br>
        <strong>Pengembang Sistem</strong><br><br><br><br>
        <strong>TAHUN {{ date('Y') }}</strong>
    </div>
</div>

<div class="page-break"></div>

<!-- ═══════════════════ DAFTAR ISI ═══════════════════ -->
<div class="toc">
    <h2>DAFTAR ISI</h2>
    <ul>
        <li><span class="num">BAB I</span> <strong>PENDAHULUAN</strong></li>
        <li class="sub-toc"><span class="num-sub">1.1</span> Latar Belakang</li>
        <li class="sub-toc"><span class="num-sub">1.2</span> Rumusan Masalah</li>
        <li class="sub-toc"><span class="num-sub">1.3</span> Tujuan Pengembangan</li>
        <li class="sub-toc"><span class="num-sub">1.4</span> Manfaat</li>

        <li style="margin-top: 10px;"><span class="num">BAB II</span> <strong>CARA KERJA SISTEM</strong></li>
        <li class="sub-toc"><span class="num-sub">2.1</span> Alur Peminjaman Normal</li>
        <li class="sub-toc"><span class="num-sub">2.2</span> Alur Pass Kunci Langsung</li>
        <li class="sub-toc"><span class="num-sub">2.3</span> Sistem Poin dan Sanksi Pemblokiran</li>
        <li class="sub-toc"><span class="num-sub">2.4</span> Peran dan Wewenang Pengguna</li>

        <li style="margin-top: 10px;"><span class="num">BAB III</span> <strong>FITUR-FITUR APLIKASI</strong></li>
        <li class="sub-toc"><span class="num-sub">3.1</span> Manajemen Akses dan Keamanan Otentikasi</li>
        <li class="sub-toc"><span class="num-sub">3.2</span> Dasbor Terpadu (Dashboard)</li>
        <li class="sub-toc"><span class="num-sub">3.3</span> Modul Pengajuan Peminjaman</li>
        <li class="sub-toc"><span class="num-sub">3.4</span> Verifikasi Kondisi Laboratorium (Checklist)</li>
        <li class="sub-toc"><span class="num-sub">3.5</span> Pass Kunci Langsung (Direct Key Transfer)</li>
        <li class="sub-toc"><span class="num-sub">3.6</span> Modul Pelaporan Manajerial</li>

        <li style="margin-top: 10px;"><span class="num">BAB IV</span> <strong>PENUTUP</strong></li>
        <li class="sub-toc"><span class="num-sub">4.1</span> Kesimpulan</li>
    </ul>
</div>

<div class="page-break"></div>

<!-- ════════════════════════════════════════════════ -->
<!-- BAB I — PENDAHULUAN -->
<!-- ════════════════════════════════════════════════ -->
<h2>BAB I<br>PENDAHULUAN</h2>

<h3>1.1 Latar Belakang</h3>
<p>Laboratorium merupakan fasilitas fundamental dalam proses pembelajaran di instansi pendidikan. Setiap harinya, puluhan siswa dan guru membutuhkan akses ke laboratorium untuk melaksanakan kegiatan praktikum dan kegiatan belajar mengajar lainnya. Dalam pelaksanaannya, proses peminjaman kunci laboratorium selama ini masih dilakukan secara konvensional, di mana siswa harus mencatat pada buku pinjaman, menunggu giliran, dan menyerahkan kunci secara tatap muka langsung dengan pengawas laboratorium.</p>

<p>Sistem pencatatan konvensional ini kerap menimbulkan berbagai kendala operasional. Buku pinjaman rentan mengalami kerusakan atau kehilangan data, tidak tersedianya rekam jejak historis yang terstruktur untuk keperluan audit, serta menuntut pengawas untuk selalu bersiaga di tempat guna mengelola proses serah terima kunci. Selain itu, tidak terdapat mekanisme yang memadai untuk memastikan bahwa kondisi ruangan laboratorium telah diperiksa dengan saksama sebelum kunci dikembalikan. Saat terjadi pergantian jadwal (shift) penggunaan laboratorium, siswa yang memegang kunci harus mengantre kembali ke meja pengawas untuk proses serah terima, yang mana hal ini sangat tidak efisien dan kerap menciptakan penumpukan antrean pada jam-jam sibuk.</p>

<h3>1.2 Rumusan Masalah</h3>
<p class="no-indent">Berdasarkan latar belakang yang telah diuraikan, rumusan masalah dalam pengembangan sistem ini adalah sebagai berikut:</p>
<ol>
    <li>Bagaimana mendigitalisasi proses peminjaman kunci laboratorium agar seluruh data transaksi tercatat secara terstruktur dan aman?</li>
    <li>Bagaimana merancang mekanisme yang mewajibkan siswa untuk memeriksa dan melaporkan kondisi laboratorium sebelum proses pengembalian kunci dilakukan?</li>
    <li>Bagaimana mencatat dan memberikan sanksi atas pelanggaran tata tertib laboratorium secara transparan melalui sistem poin?</li>
    <li>Bagaimana membangun fitur yang memungkinkan siswa mengalihkan atau mentransfer kunci kepada siswa lain tanpa harus melalui perantara pengawas secara fisik?</li>
    <li>Bagaimana menyediakan modul pelaporan yang komprehensif bagi administrator dan pengawas mengenai seluruh aktivitas peminjaman?</li>
</ol>

<h3>1.3 Tujuan Pengembangan</h3>
<p>Tujuan utama dari proyek ini adalah membangun sistem informasi berbasis web yang mampu mengelola keseluruhan siklus peminjaman kunci laboratorium secara digital. Siklus tersebut mencakup proses pengajuan, persetujuan, serah terima kunci, pemeriksaan kondisi ruangan (checklist), pencatatan pelanggaran, hingga transfer kunci antar siswa (Pass Kunci Langsung). Melalui digitalisasi ini, diharapkan proses operasional laboratorium menjadi lebih efisien, transparan, dan terdokumentasi dengan akurat.</p>

<h3>1.4 Manfaat</h3>
<p class="no-indent">Manfaat yang diharapkan dari implementasi sistem Si Kunci Lab ini mencakup berbagai pihak, antara lain:</p>
<ul>
    <li><strong>Bagi Siswa:</strong> Memberikan kemudahan dalam mengajukan peminjaman secara daring, melihat status peminjaman secara seketika (real-time), mengisi formulir pemeriksaan kondisi laboratorium melalui perangkat seluler, serta memfasilitasi transfer kunci kepada sesama siswa tanpa perlu mengantre di meja pengawas.</li>
    <li><strong>Bagi Pengawas:</strong> Memudahkan proses persetujuan peminjaman dari mana saja, memantau kondisi laboratorium melalui bukti foto yang diunggah siswa, dan memonitor rekam jejak serah terima kunci secara menyeluruh.</li>
    <li><strong>Bagi Administrator:</strong> Mempermudah pengelolaan data pengguna dan data laboratorium, serta otomatisasi pembuatan laporan operasional, laporan keterlambatan, dan rekapitulasi pelanggaran dalam bentuk dokumen cetak.</li>
    <li><strong>Bagi Institusi Sekolah:</strong> Menjamin tersedianya arsip digital yang rapi, meminimalisasi risiko kehilangan data operasional, dan meningkatkan akuntabilitas penggunaan fasilitas laboratorium.</li>
</ul>

<!-- ════════════════════════════════════════════════ -->
<!-- BAB II — CARA KERJA SISTEM -->
<!-- ════════════════════════════════════════════════ -->
<div class="page-break"></div>
<h2>BAB II<br>CARA KERJA SISTEM</h2>

<h3>2.1 Alur Peminjaman Normal</h3>
<p>Proses operasional standar pada sistem Si Kunci Lab dirancang untuk memastikan setiap perpindahan tanggung jawab tercatat dengan baik. Alur peminjaman berjalan secara berurutan sebagai berikut:</p>

<p class="flow">Siswa Mengajukan &rarr; Pengawas Menyetujui &rarr; Pengawas Menyerahkan Kunci &rarr; Peminjaman Berlangsung &rarr; Siswa Mengisi Checklist &rarr; Pengawas Menerima Kunci &rarr; Selesai</p>

<p>Setiap siswa yang telah terdaftar dapat mengakses sistem menggunakan kredensial masing-masing. Melalui halaman dasbor, siswa dapat mengajukan peminjaman dengan menentukan laboratorium yang dituju beserta sesi waktu yang tersedia. Pengajuan tersebut akan masuk ke dalam antrean pengawas untuk diproses (disetujui atau ditolak). Apabila disetujui, pengawas akan menyerahkan kunci secara fisik, dan mencatat penyerahan tersebut di dalam sistem, sehingga status peminjaman berubah menjadi "Berlangsung".</p>

<p>Sebelum mengembalikan kunci kepada pengawas, siswa diwajibkan untuk mengisi formulir pemeriksaan (checklist) kondisi laboratorium yang mencakup lima indikator: status kelistrikan, pendingin ruangan (AC), kelayakan perangkat komputer, kebersihan ruangan, serta keamanan pintu dan jendela. Siswa juga diwajibkan mengunggah foto sebagai bukti visual. Setelah persyaratan tersebut dipenuhi, pengawas dapat menerima kunci kembali dan sistem akan otomatis mencatat durasi pengembalian, apakah tepat waktu atau mengalami keterlambatan.</p>

<h3>2.2 Alur Pass Kunci Langsung</h3>
<p>Fitur inovatif dari sistem ini adalah Pass Kunci Langsung, yang memungkinkan transfer tanggung jawab kunci dari satu siswa ke siswa lain tanpa intervensi langsung dari pengawas. Alur kerjanya adalah sebagai berikut:</p>

<p class="flow">Siswa A Mengisi Checklist &rarr; Siswa A Membuat Kode QR &rarr; Siswa B Memindai Kode QR &rarr; Sistem Melakukan Validasi &rarr; Peminjaman A Selesai, Peminjaman B Berlangsung</p>

<p>Siswa A (pemegang kunci yang sah saat ini) harus terlebih dahulu menyelesaikan formulir checklist kondisi laboratorium. Setelah selesai, sistem akan menghasilkan Kode QR dinamis di layar perangkat Siswa A. Selanjutnya, Siswa B yang ingin mengambil alih kunci membuka menu "Ambil Kunci" pada perangkatnya dan memindai Kode QR tersebut. Sistem akan memvalidasi keabsahan Kode QR yang memiliki batas waktu 5 menit. Jika divalidasi berhasil, sistem secara otomatis menutup sesi peminjaman Siswa A, mengaktifkan sesi peminjaman baru atas nama Siswa B, dan menonaktifkan Kode QR tersebut agar tidak dapat disalahgunakan.</p>

<h3>2.3 Sistem Poin dan Sanksi Pemblokiran</h3>
<p>Untuk menegakkan tata tertib penggunaan laboratorium, sistem dilengkapi dengan mekanisme poin pelanggaran. Pengawas berwenang untuk mencatat pelanggaran yang dilakukan oleh siswa selama masa peminjaman. Pelanggaran diklasifikasikan ke dalam empat kategori: keterlambatan pengembalian kunci, kerusakan alat/fasilitas, kebersihan ruangan, dan pelanggaran lainnya. Setiap pelanggaran memiliki bobot poin tertentu (secara bawaan adalah 1 poin).</p>

<p>Poin pelanggaran ini bersifat akumulatif pada profil siswa. Apabila total poin pelanggaran seorang siswa mencapai angka 5 atau lebih, sistem akan secara otomatis menangguhkan (memblokir) hak akses siswa tersebut untuk melakukan peminjaman baru. Pemulihan hak akses hanya dapat dilakukan oleh Administrator dengan melakukan proses pengaturan ulang (reset) poin setelah siswa yang bersangkutan memenuhi sanksi indisipliner yang ditetapkan oleh pihak sekolah.</p>

<h3>2.4 Peran dan Wewenang Pengguna</h3>
<p class="no-indent">Sistem ini membagi wewenang pengguna ke dalam tiga tingkatan peran untuk menjamin keamanan dan ketepatan alur kerja:</p>

<table>
    <tr>
        <th style="width: 25%">Peran Pengguna</th>
        <th>Deskripsi Wewenang dan Tugas Utama</th>
    </tr>
    <tr>
        <td><strong>Administrator</strong></td>
        <td>Memiliki hak akses penuh untuk mengelola master data pengguna (tambah, ubah, hapus), mengelola master data laboratorium, mencetak laporan manajerial, melakukan reset poin pelanggaran, serta membuka blokir akun siswa.</td>
    </tr>
    <tr>
        <td><strong>Pengawas</strong></td>
        <td>Bertanggung jawab atas operasional harian. Wewenangnya meliputi persetujuan/penolakan pengajuan, pencatatan serah terima kunci, verifikasi checklist dan bukti foto, pencatatan pelanggaran siswa, serta pemantauan log rekam jejak transfer kunci.</td>
    </tr>
    <tr>
        <td><strong>Siswa (Murid)</strong></td>
        <td>Bertindak sebagai peminjam fasilitas. Wewenangnya meliputi pengajuan peminjaman, pemantauan riwayat peminjaman pribadi, pengisian checklist pengembalian, pembuatan Kode QR untuk pass kunci, serta pemindaian Kode QR untuk mengambil alih kunci.</td>
    </tr>
</table>

<!-- ════════════════════════════════════════════════ -->
<!-- BAB III — FITUR-FITUR APLIKASI -->
<!-- ════════════════════════════════════════════════ -->
<div class="page-break"></div>
<h2>BAB III<br>FITUR-FITUR APLIKASI</h2>

<h3>3.1 Manajemen Akses dan Keamanan Otentikasi</h3>
<p>Setiap pengguna mengakses sistem melalui gerbang otentikasi terpusat menggunakan alamat surel dan kata sandi yang dienkripsi. Sistem dilengkapi dengan kontrol akses berbasis peran (Role-Based Access Control) yang mengarahkan pengguna ke dasbor spesifik sesuai kapasitasnya. Sistem secara aktif mencegah akses masuk bagi akun siswa yang sedang berada dalam masa penangguhan (diblokir) akibat akumulasi poin pelanggaran.</p>

<h3>3.2 Dasbor Terpadu (Dashboard)</h3>
<p>Antarmuka dasbor dirancang secara spesifik untuk masing-masing peran. Dasbor Administrator menyajikan metrik makro terkait total peminjaman dan pelanggaran. Dasbor Pengawas memfokuskan antarmuka pada daftar permohonan yang menunggu persetujuan dan kunci yang sedang dipinjam. Sementara itu, dasbor Siswa menampilkan ringkasan metrik pribadi dan menyediakan fitur peminjaman cepat (Quick Borrow) yang memungkinkan siswa meminjam laboratorium kosong hanya dalam tiga langkah klik.</p>

<h3>3.3 Modul Pengajuan Peminjaman</h3>
<p>Modul ini menangani proses permintaan fasilitas. Siswa diwajibkan memilih laboratorium, menetapkan sesi waktu standar (misalnya 07:00–08:30), dan memberikan keterangan tujuan penggunaan. Modul ini terintegrasi dengan fungsi validasi bentrokan jadwal yang secara otomatis menolak permintaan apabila laboratorium telah dipesan oleh pihak lain pada rentang waktu yang tumpang tindih. Aturan sistem juga membatasi siswa agar hanya dapat memiliki satu transaksi aktif dalam satu waktu.</p>

<h3>3.4 Verifikasi Kondisi Laboratorium (Checklist)</h3>
<p>Fitur ini merupakan mekanisme kontrol kualitas untuk menjaga fasilitas sekolah. Sebelum sesi peminjaman diakhiri, peminjam wajib mendeklarasikan lima kondisi fundamental: status kelistrikan, status pendingin ruangan, kelayakan perangkat komputer, kebersihan umum, dan keamanan penguncian ruangan. Ketentuan ini diperkuat dengan kewajiban melampirkan dokumentasi fotografi dari kamera perangkat, yang kelak menjadi rujukan bagi pengawas maupun peminjam berikutnya.</p>

<h3>3.5 Pass Kunci Langsung (Direct Key Transfer)</h3>
<p>Fitur Pass Kunci Langsung dirancang untuk mengoptimalkan utilitas waktu selama pergantian jadwal praktik. Alih-alih mengembalikan kunci ke meja pengawas, siswa dapat mendistribusikannya langsung kepada siswa penjadwal berikutnya melalui instrumen Kode QR.</p>

<h4>Tahapan Operasional Pass Kunci:</h4>
<ol>
    <li><strong>Deklarasi Kondisi (Checklist):</strong> Proses transfer tidak dapat diinisiasi sebelum pemegang kunci saat ini menyelesaikan formulir pemeriksaan dan mengunggah dokumentasi ruangan.</li>
    <li><strong>Pembangkitan Kriptografi QR:</strong> Sistem menghasilkan Kode QR dinamis yang mengandung token kriptografi acak (48 karakter). Token ini bersifat sekali pakai (single-use) dan dilengkapi mekanisme waktu kedaluwarsa (time-to-live) selama 5 menit.</li>
    <li><strong>Proses Pemindaian:</strong> Siswa penerima mengaktifkan modul pemindai pada perangkat genggamnya untuk membaca Kode QR dari layar perangkat pihak penyerah.</li>
    <li><strong>Validasi dan Eksekusi:</strong> Server mengevaluasi keabsahan token. Jika kriteria terpenuhi, sistem akan melakukan transaksi basis data tunggal (atomic transaction) yang menutup kontrak peminjam lama, membuka kontrak peminjam baru, dan merekam jejak serah terima tanpa celah untuk duplikasi (race condition).</li>
</ol>

<h3>3.6 Modul Pelaporan Manajerial</h3>
<p>Modul pelaporan dirancang khusus untuk Administrator guna menghasilkan dokumen rekapitulasi yang valid. Terdapat tiga varian laporan: Laporan Transaksi Peminjaman, Laporan Keterlambatan Pengembalian, dan Laporan Rekapitulasi Pelanggaran. Seluruh dokumen laporan diformat khusus untuk pencetakan (print-ready) pada kertas ukuran A4 format lanskap, siap untuk ditandatangani dan diarsipkan oleh manajemen sekolah.</p>

<!-- ════════════════════════════════════════════════ -->
<!-- BAB IV — PENUTUP -->
<!-- ════════════════════════════════════════════════ -->
<div class="page-break"></div>
<h2>BAB IV<br>PENUTUP</h2>

<h3>4.1 Kesimpulan</h3>
<p>Pengembangan aplikasi Si Kunci Lab berhasil mentransformasi tata kelola operasional laboratorium sekolah dari pencatatan berbasis kertas menjadi sebuah ekosistem digital yang terintegrasi. Sistem ini tidak hanya mendigitalisasi proses administratif, tetapi juga memperkenalkan inovasi penyelesaian masalah antrean melalui fitur Pass Kunci Langsung berbasis Kode QR.</p>

<p>Penerapan modul checklist dan sistem poin pelanggaran secara signifikan meningkatkan kesadaran serta tanggung jawab siswa terhadap perawatan fasilitas inventaris sekolah. Sementara itu, ketersediaan modul pelaporan otomatis memberikan kemudahan bagi manajemen institusi pendidikan dalam melakukan audit dan evaluasi operasional laboratorium secara berkala.</p>

<p>Secara keseluruhan, aplikasi ini memberikan solusi perangkat lunak yang andal, aman, dan tepat guna dalam menciptakan lingkungan manajemen aset laboratorium yang profesional dan berakuntabilitas tinggi.</p>

</body>
</html>
