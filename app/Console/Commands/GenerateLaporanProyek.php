<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerateLaporanProyek extends Command
{
    protected $signature = 'laporan:proyek';
    protected $description = 'Generate Laporan Proyek SiKunci Lab in PDF';

    public function handle()
    {
        $this->info('Generating PDF...');

        $pdf = Pdf::loadView('laporan.proyek_pdf_simple');
        $outputPath = base_path('laporan-simpel-sikunci.pdf');
        $pdf->save($outputPath);

        $this->info("PDF saved to: {$outputPath}");
        return Command::SUCCESS;
    }
}
