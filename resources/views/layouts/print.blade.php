<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cetak Laporan') - SiKunci Lab</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f8fafc;
            color: #111827;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.35;
        }

        .print-actions {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding: 10px 14px;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
        }

        .print-actions button {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background: #ffffff;
            color: #0f172a;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            padding: 7px 12px;
        }

        .sheet {
            width: 100%;
            min-height: 100vh;
            padding: 18px;
            background: #ffffff;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            padding-bottom: 12px;
            border-bottom: 2px solid #111827;
            margin-bottom: 12px;
        }

        .brand-title {
            margin: 0;
            font-size: 18px;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .report-title {
            margin: 4px 0 0;
            font-size: 14px;
            font-weight: 700;
        }

        .print-meta {
            min-width: 220px;
            text-align: right;
        }

        .print-meta div {
            margin-bottom: 2px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
            margin-bottom: 12px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
            margin-bottom: 12px;
        }

        .filter-item,
        .summary-item {
            border: 1px solid #d1d5db;
            padding: 7px 8px;
        }

        .filter-label,
        .summary-label {
            display: block;
            color: #64748b;
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .summary-value {
            display: block;
            font-size: 15px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        tr {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        th,
        td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f1f5f9;
            color: #334155;
            font-size: 10px;
            text-transform: uppercase;
        }

        .nowrap {
            white-space: nowrap;
        }

        .muted {
            color: #64748b;
        }

        .strong {
            font-weight: 700;
        }

        .empty-state {
            padding: 18px;
            text-align: center;
            color: #64748b;
        }

        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 220px;
            gap: 24px;
            margin-top: 26px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-space {
            height: 54px;
        }

        .signature-name {
            border-top: 1px solid #111827;
            padding-top: 5px;
            font-weight: 700;
        }

        @media print {
            body {
                background: #ffffff;
                font-size: 10px;
            }

            .print-actions {
                display: none !important;
            }

            .sheet {
                min-height: auto;
                padding: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="print-actions">
        <button type="button" onclick="window.print()">Cetak</button>
        <button type="button" onclick="window.close()">Tutup</button>
    </div>

    <main class="sheet">
        @yield('content')
    </main>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () {
                window.print();
            }, 250);
        });
    </script>
</body>
</html>
