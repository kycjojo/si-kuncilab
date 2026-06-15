$ErrorActionPreference = "Stop"

$DIR = Split-Path -Parent $MyInvocation.MyCommand.Path
$OUTDIR = $DIR
$REPORT = "$OUTDIR\laporan-project.pdf"

# Create temp directory
$TMPDIR = Join-Path $env:TEMP "laporan-build-$([System.IO.Path]::GetRandomFileName())"
New-Item -ItemType Directory -Path $TMPDIR -Force | Out-Null

try {
    Copy-Item "$OUTDIR\cover.md" "$TMPDIR\" -Force
    Copy-Item "$OUTDIR\template.latex" "$TMPDIR\" -Force
    Copy-Item "$OUTDIR\logo-kampus.jpg" "$TMPDIR\" -Force

    $COMBINED = "$TMPDIR\report.md"

    # Strip YAML front matter
    function Strip-Yaml($file) {
        $content = Get-Content $file -Raw
        $content = $content -replace '(?ms)^---.*?^---\s*', ''
        $content = $content -replace '(?m)^#\+ BAB.*', ''
        return $content
    }

    $sb = [System.Text.StringBuilder]::new()

    [void]$sb.AppendLine("# PENDAHULUAN")
    [void]$sb.AppendLine()
    [void]$sb.AppendLine((Strip-Yaml "$OUTDIR\01-pendahuluan.md"))

    [void]$sb.AppendLine()
    [void]$sb.AppendLine("# ALUR PEMINJAMAN KUNCI")
    [void]$sb.AppendLine()
    [void]$sb.AppendLine((Strip-Yaml "$OUTDIR\02-peminjaman.md"))

    [void]$sb.AppendLine()
    [void]$sb.AppendLine("# ALUR PENGEMBALIAN KUNCI")
    [void]$sb.AppendLine()
    [void]$sb.AppendLine((Strip-Yaml "$OUTDIR\03-pengembalian.md"))

    [void]$sb.AppendLine()
    [void]$sb.AppendLine("# ALUR PASSING KUNCI MENGGUNAKAN QR")
    [void]$sb.AppendLine()
    [void]$sb.AppendLine((Strip-Yaml "$OUTDIR\04-passing-qr.md"))

    [void]$sb.AppendLine()
    [void]$sb.AppendLine("# FITUR-FITUR LAINNYA")
    [void]$sb.AppendLine()
    [void]$sb.AppendLine((Strip-Yaml "$OUTDIR\05-fitur-lain.md"))

    [void]$sb.AppendLine()
    [void]$sb.AppendLine((Strip-Yaml "$OUTDIR\daftar-pustaka.md"))

    Set-Content -Path $COMBINED -Value $sb.ToString() -Encoding UTF8

    Set-Location -Path $TMPDIR

    $HEADER_TEXT = "Si Kunci Lab - Sistem Informasi Manajemen Peminjaman Kunci Laboratorium"

    $template = Get-Content "template.latex" -Raw
    $template = $template.Replace('${HEADER_TEXT}', $HEADER_TEXT)
    Set-Content -Path "template.latex" -Value $template -Encoding UTF8

    $cover = Get-Content "cover.md" -Raw
    $cover = $cover.Replace('${HEADER_TEXT}', $HEADER_TEXT)
    Set-Content -Path "cover.md" -Value $cover -Encoding UTF8

    & pandoc $COMBINED `
        --template="$TMPDIR\template.latex" `
        --include-before-body="$TMPDIR\cover.md" `
        --top-level-division=chapter `
        --pdf-engine=pdflatex `
        -o $REPORT 2>&1

    Write-Output "Build berhasil! PDF: $REPORT"
}
catch {
    Write-Error "Build gagal: $_"
    exit 1
}
finally {
    Set-Location -Path $DIR
    Remove-Item -Path $TMPDIR -Recurse -Force -ErrorAction SilentlyContinue
}
