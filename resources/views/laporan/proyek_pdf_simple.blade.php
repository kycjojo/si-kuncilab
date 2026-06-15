<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Alur Operasional — Si Kunci Lab</title>
    <style>
        @page {
            margin: 30mm 25mm 25mm 25mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000000;
            line-height: 1.6;
        }

        /* ── Header / Cover ── */
        .title-block {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px double #000;
        }
        .title-block h1 {
            font-size: 20pt;
            font-weight: bold;
            margin: 0 0 8px;
            text-transform: uppercase;
        }
        .title-block .subtitle {
            font-size: 12pt;
            font-style: italic;
            color: #333;
        }

        h2 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 30px 0 15px;
            page-break-after: avoid;
        }

        h3 {
            font-size: 12pt;
            font-weight: bold;
            margin: 20px 0 10px;
            page-break-after: avoid;
        }

        p {
            margin: 10px 0;
            text-align: justify;
            text-indent: 1.25cm;
        }
        .no-indent {
            text-indent: 0;
        }

        /* ── Lists ── */
        ol, ul {
            margin: 10px 0 10px 1.25cm;
            padding-left: 20px;
            text-align: justify;
        }
        li {
            margin-bottom: 8px;
        }

        /* ── Formal Box ── */
        .formal-note {
            border: 1px solid #000;
            padding: 12px 18px;
            margin: 20px 0;
            background: #FAFAFA;
        }
        .formal-note strong {
            display: block;
            margin-bottom: 4px;
            text-transform: uppercase;
            font-size: 11pt;
        }

        .page-break { page-break-before: always; }

        .footer {
            text-align: center;
            font-size: 9pt;
            font-style: italic;
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <!-- ═══════════════════ HEADER ═══════════════════ -->
    <div class="title-block">
        <h1>LAPORAN ALUR OPERASIONAL</h1>
        <div class="subtitle">Sistem Informasi Peminjaman Kunci Laboratorium (Si Kunci Lab)</div>
    </div>

    <!-- ═══════════════════ 1. CARA KERJA WEB ═══════════════════ -->
    <h2>1. Cara Kerja Web</h2>
    <p>Aplikasi <strong>Si Kunci Lab</strong> dirancang sebagai platform manajemen operasional yang menjembatani interaksi antara siswa (peminjam), pengawas (verifikator), dan administrator sekolah dalam hal penggunaan laboratorium. Sistem bekerja secara terpusat menggunakan basis data real-time untuk mencatat perpindahan tanggung jawab atas aset kunci laboratorium.</p>

    <p>Secara umum, sistem ini bekerja dengan membagi hak akses ke dalam tiga peran utama:</p>
    <ul>
        <li><strong>Siswa (Murid):</strong> Mengajukan permintaan izin penggunaan ruangan secara mandiri melalui dasbor, memantau masa peminjaman, mengisi checklist kelayakan lab, dan melakukan transfer kunci secara langsung kepada sesama siswa.</li>
        <li><strong>Pengawas:</strong> Bertindak sebagai pengontrol harian yang memvalidasi permohonan pinjam, mencatat waktu penyerahan kunci fisik, memeriksa foto bukti kelayakan ruangan, serta memberikan poin sanksi jika terjadi pelanggaran tata tertib.</li>
        <li><strong>Administrator:</strong> Mengelola data induk (master data) pengguna dan laboratorium, mengendalikan status blokir akun siswa, serta mencetak laporan rekapitulasi operasional secara berkala.</li>
    </ul>

    <!-- ═══════════════════ 2. ALUR PEMINJAMAN ═══════════════════ -->
    <h2>2. Alur Peminjaman Kunci</h2>
    <p>Prosedur peminjaman kunci laboratorium pada awal sesi penggunaan berjalan secara bertahap melalui mekanisme verifikasi ganda. Tahapan tersebut adalah sebagai berikut:</p>

    <ol>
        <li><strong>Pengajuan Permohonan:</strong> Siswa mengakses dasbor, memilih laboratorium yang berstatus aktif, menentukan sesi waktu pemakaian, dan mendeskripsikan tujuan pemakaian. Sistem secara otomatis menolak pengajuan apabila terjadi tumpang tindih jadwal dengan peminjaman lain yang telah disetujui.</li>
        <li><strong>Persetujuan Pengawas:</strong> Permohonan yang masuk akan ditinjau oleh pengawas melalui panel kontrol. Pengawas dapat menyetujui pengajuan tersebut atau menolaknya dengan menyertakan catatan tertulis.</li>
        <li><strong>Registrasi Serah Terima:</strong> Siswa menemui pengawas untuk mengambil kunci fisik laboratorium. Pengawas kemudian menekan tombol konfirmasi penyerahan pada sistem. Waktu penyerahan akan tercatat secara akurat di dalam database, dan status peminjaman berubah menjadi aktif ("Berlangsung").</li>
    </ol>

    <div class="page-break"></div>

    <!-- ═══════════════════ 3. ALUR PENGEMBALIAN ═══════════════════ -->
    <h2>3. Alur Pengembalian Kunci</h2>
    <p>Mekanisme pengembalian kunci dirancang untuk menjamin pemeliharaan kebersihan dan keselamatan fasilitas laboratorium setelah selesai digunakan. Alur pengembalian kunci diatur melalui langkah-langkah wajib sebagai berikut:</p>

    <ol>
        <li><strong>Pemeriksaan Mandiri (Checklist):</strong> Sebelum meninggalkan laboratorium, siswa yang meminjam wajib mengisi formulir checklist kondisi ruangan melalui perangkat seluler mereka. Indikator pemeriksaan meliputi penonaktifan arus listrik, penonaktifan pendingin ruangan (AC), kerapian perangkat komputer/praktikum, kebersihan umum ruangan, serta penguncian seluruh jendela dan pintu masuk.</li>
        <li><strong>Dokumentasi Bukti Fisik:</strong> Peminjam diwajibkan mengambil foto kondisi laboratorium terkini menggunakan kamera ponsel dan mengunggahnya langsung ke sistem sebagai lampiran wajib dari formulir checklist.</li>
        <li><strong>Verifikasi Akhir:</strong> Kunci fisik dikembalikan kepada pengawas. Pengawas memeriksa kesesuaian checklist serta bukti foto ruangan pada sistem. Apabila kondisi ruangan dinyatakan aman, pengawas menutup sesi peminjaman. Sistem secara otomatis membandingkan waktu selesai aktual dengan rencana peminjaman untuk mendeteksi adanya keterlambatan.</li>
    </ol>

    <!-- ═══════════════════ 4. ALUR PASSING KUNCI ═══════════════════ -->
    <h2>4. Alur Passing Kunci (Direct Transfer)</h2>
    <p>Fitur <i>Pass Kunci Langsung</i> memfasilitasi transfer tanggung jawab peminjaman kunci antar siswa secara langsung tanpa perantara fisik pengawas. Prosedur ini sangat efisien untuk pergantian sesi praktikum yang berurutan. Alurnya didefinisikan sebagai berikut:</p>

    <ol>
        <li><strong>Penyelesaian Checklist:</strong> Siswa A (pemegang kunci saat ini) wajib menyelesaikan formulir checklist kelayakan ruangan dan mengunggah foto kondisi lab di akhir sesi penggunaannya. Tombol untuk melakukan transfer kunci tidak akan aktif sebelum kewajiban ini dipenuhi.</li>
        <li><strong>Pembangkitan Kode QR:</strong> Siswa A menekan tombol "Pass Kunci". Sistem memvalidasi kesiapan data, kemudian membangkitkan Kode QR dinamis sekali pakai (single-use) yang berlaku terbatas selama 5 menit.</li>
        <li><strong>Pemindaian oleh Penerima:</strong> Siswa B (penerima kunci sesi berikutnya) membuka menu "Ambil Kunci" pada dasbor akunnya untuk mengaktifkan pemindai kamera, lalu mengarahkan kamera ke layar perangkat Siswa A untuk memindai Kode QR tersebut.</li>
        <li><strong>Otomatisasi Transaksi:</strong> Server memproses pemindaian. Jika validasi berhasil, sistem secara simultan menutup sesi peminjaman Siswa A (dinyatakan selesai) dan secara otomatis membuat sesi peminjaman baru atas nama Siswa B (dinyatakan berlangsung) dengan menyalin detail ruang laboratorium yang bersangkutan.</li>
    </ol>

    <div class="formal-note">
        <strong>Pernyataan Akuntabilitas</strong>
        Meskipun kunci fisik berpindah tangan tanpa melalui pengawas secara langsung, akuntabilitas tetap terjaga. Sistem mencatat rekam jejak digital lengkap (siapa menyerahkan kepada siapa, jam transaksi, serta bukti foto lab yang diunggah oleh pihak penyerah), sehingga apabila terjadi kerusakan fasilitas, penelusuran tanggung jawab dapat dilakukan dengan mudah.
    </div>

    <!-- ═══════════════════ FOOTER ═══════════════════ -->
    <div class="footer">
        Laporan Alur Kerja Sistem Informasi Si Kunci Lab
    </div>

</body>
</html>
