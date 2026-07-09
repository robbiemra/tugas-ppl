<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ringkasan Cerita - {{ $history->user_name }}</title>
    <style>
        /* Mengatur Orientasi Landscape */
        @page {
            size: A4 landscape;
            margin: 12mm 15mm; /* Margin langsung ditaruh di level kertas */
        }
        
        body { 
            font-family: 'Courier', monospace; 
            color: #2c2520; 
            background-color: #fbfaf5;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
        }

        /* Container Utama Per Bagian Cerita */
        .page-wrapper {
            page-break-after: always;
            box-sizing: border-box;
            width: 100%; /* Mengikuti lebar kertas @page secara otomatis */
            height: 100%;
        }

        /* Mencegah lembar kosong di akhir halaman dokumen */
        .page-wrapper:last-child {
            page-break-after: avoid !important;
            margin-bottom: 0 !important;
        }

        .book-frame {
            border: 4px solid #8b0000;
            padding: 15px 20px;
            background-color: #fdfcf7;
            border-radius: 5px;
            box-sizing: border-box;
            display: block;
            width: 100%;
        }

        /* Header & Metadata */
        .header {
            text-align: center;
            border-bottom: 2px dashed #8b0000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .title {
            font-family: 'Times', serif;
            font-size: 22px;
            font-weight: bold;
            color: #8b0000;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin: 0;
        }
        .meta-table {
            width: 100%;
            font-family: 'Helvetica', sans-serif;
            font-size: 10px;
            background-color: #f3efe6;
            margin-bottom: 12px;
            border-left: 5px solid #8b0000;
            color: #2c2520;
            border-collapse: collapse;
            table-layout: fixed; /* Mencegah kolom melebar acak */
        }
        .meta-table td {
            padding: 5px 10px;
            width: 50%;
        }

        /* STRUKTUR TABEL ANTI-TERPOTONG (FIXED) */
        .layout-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Kunci kolom agar teks otomatis wrap/pindah baris di dalam box */
        }
        .col-image {
            width: 42%; /* Rasio lebar kolom kiri */
            vertical-align: top;
            padding-right: 20px;
        }
        .col-text {
            width: 58%; /* Rasio lebar kolom kanan */
            vertical-align: top;
        }
        
        .image-container {
            text-align: center;
            width: 100%;
        }
        .story-image {
            width: 100%; /* Mengikuti lebar 42% kolom kiri secara proporsional */
            height: auto;
            max-height: 120mm; /* Batasi tinggi maksimum agar tidak mendorong kertas ke bawah */
            border: 3px solid #8b0000;
        }
        
        .chapter-badge {
            display: inline-block;
            background-color: #8b0000;
            color: #ffffff;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .story-text {
            font-size: 11pt; 
            line-height: 1.5;
            text-align: justify;
            word-wrap: break-word; /* Memaksa kata yang terlalu panjang untuk patah baris */
        }
        
        .choice-box {
            margin-top: 12px;
            padding: 8px 12px;
            background-color: rgba(139, 0, 0, 0.05);
            border-left: 3px solid #8b0000;
            font-style: italic;
            color: #8b0000;
            font-size: 10.5pt;
        }
        
        /* Footer Penutup */
        .footer-tamat {
            text-align: center;
            margin-top: 15px;
            padding-top: 8px;
            border-top: 2px dashed #8b0000;
            color: #8b0000;
        }
        .tamat-title {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 3px;
        }
        .tamat-sub {
            font-size: 10.5px;
            font-style: italic;
            margin-top: 2px;
        }
        
        .page-number {
            text-align: right;
            font-size: 10px;
            color: #5c524b;
            font-family: 'Helvetica', sans-serif;
            margin-top: 10px;
        }
    </style>
</head>
<body>

@if(isset($storyPages) && count($storyPages) > 0)
    @foreach($storyPages as $index => $page)
        <div class="page-wrapper">
            <div class="book-frame">
                
                <!-- Header Identitas Hanya di Bagian 1 -->
                @if($index === 0)
                    <div class="header">
                        <div class="title">JURNAL PETUALANGAN: {{ strtoupper($history->selected_location ?? 'DUNIA MISTERI') }}</div>
                    </div>
                    <table class="meta-table">
                        <tr>
                            <td><strong>NAMA USER:</strong> {{ $history->user_name }}</td>
                            <td><strong>GENRE CERITA:</strong> {{ ucfirst($history->selected_genre ?? 'Interactive Story') }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>WAKTU KEJADIAN:</strong> {{ now()->format('d F Y - H:i:s') }}</td>
                        </tr>
                    </table>
                @endif

                <!-- Konten Utama Alur Cerita -->
                <table class="layout-table">
                    <tr>
                        <!-- Kolom Kiri: Gambar Gambar -->
                        <td class="col-image">
                            @if(isset($page['image_base64']) && $page['image_base64'])
                                <div class="image-container">
                                    <img src="{{ $page['image_base64'] }}" class="story-image" alt="Scene {{ $index + 1 }}">
                                </div>
                            @elseif(isset($page['image']) && $page['image'])
                                <div class="image-container">
                                    <img src="{{ $page['image'] }}" class="story-image" alt="Scene {{ $index + 1 }}">
                                </div>
                            @endif
                        </td>
                        
                        <!-- Kolom Kanan: Narasi Teks -->
                        <td class="col-text">
                            <div class="chapter-badge">Bagian {{ $index + 1 }}</div>
                            <div class="story-text">
                                {!! nl2br(e($page['content'])) !!}
                            </div>
                            
                            @if(isset($page['choice']) && $page['choice'])
                                <div class="choice-box">
                                    <strong>>> Pilihan yang diambil:</strong> "{{ $page['choice'] }}"
                                </div>
                            @endif

                            <!-- Footer Khusus Akhir Halaman Cerita -->
                            @if($index === count($storyPages) - 1)
                                <div class="footer-tamat">
                                    <div class="tamat-title">--- TAMAT ---</div>
                                    <div class="tamat-sub">Akhir dari lembaran cerita perjalanan Anda.</div>
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>
                
                <!-- Penomoran Alur Halaman -->
                <div class="page-number">
                    Halaman {{ $index + 1 }} / {{ count($storyPages) }}
                </div>
            </div>
        </div>
    @endforeach
@else
    <!-- Fallback tunggal jika json kosong -->
    <div class="page-wrapper">
        <div class="book-frame">
            <div class="header">
                <div class="title">RINGKASAN CERITA</div>
            </div>
            <div class="story-text">
                {!! nl2br(e($history->accumulated_story)) !!}
            </div>
            <div class="footer-tamat" style="margin-top: 40px;">
                <div class="title">--- TAMAT ---</div>
                <div class="subtitle">Akhir dari lembaran cerita perjalanan Anda.</div>
            </div>
        </div>
    </div>
@endif

</body>
</html>