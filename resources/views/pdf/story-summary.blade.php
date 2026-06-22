<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pendakian Horor - {{ $history->user_name }}</title>
    <style>
        @page {
            size: A4;
            margin: 20mm 20mm 20mm 20mm;
            background-color: #fbfaf5;
        }
        body { 
            font-family: 'Courier', monospace; 
            color: #2c2520; /* Warna tulisan gelap vintage */
            line-height: 1.8; 
            background-color: #fbfaf5;
        }
        .book-frame {
            border: 4px solid #8b0000;
            padding: 30px;
            min-height: 100vh;
            box-sizing: border-box;
            background-color: #fdfcf7;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #8b0000;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .title {
            font-family: 'Times', serif;
            font-size: 32px;
            font-weight: bold;
            color: #8b0000;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin: 0;
            text-shadow: 1px 1px 2px #ccc;
        }
        .subtitle {
            font-size: 14px;
            color: #5c524b;
            font-style: italic;
            margin-top: 10px;
            letter-spacing: 2px;
        }
        .meta-box {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            background-color: #f3efe6;
            padding: 15px;
            margin-bottom: 30px;
            border-left: 5px solid #8b0000;
            color: #2c2520;
        }
        .story-text {
            font-size: 14pt;
            text-align: justify;
            text-indent: 30px;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 12px;
            color: #8b0000;
            border-top: 2px dashed #8b0000;
            padding-top: 15px;
            font-weight: bold;
            letter-spacing: 3px;
        }
        
        .story-page {
            page-break-after: always;
            margin-bottom: 30px;
        }
        .story-page:last-child {
            page-break-after: auto;
        }
        
        .page-layout {
            width: 100%;
        }
        
        .image-container {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .story-image {
            width: 100%;
            max-width: 400px;
            border: 3px solid #8b0000;
            box-shadow: 3px 3px 10px #bbb;
        }
        
        .chapter-label {
            text-align: center; 
            margin-top: 10px; 
            font-size: 12px; 
            color: #8b0000;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .page-number {
            text-align: center; 
            margin-top: 30px; 
            font-size: 11px; 
            color: #5c524b;
            border-top: 1px solid #8b0000;
            padding-top: 5px;
        }
    </style>
</head>
<body>
<div class="book-frame">
    <!-- Cover -->
    <div class="header">
        <div class="title">PENDAKIAN HOROR</div>
        <div class="subtitle">Catatan Kematian: {{ $history->user_name }}</div>
    </div>
    
    <div class="meta-box">
        <strong>DATA PENDAKI:</strong><br>
        Nama: {{ $history->user_name }}<br>
        Gender: {{ $history->gender }}<br>
        Waktu Kejadian: {{ now()->format('d F Y - H:i:s') }}<br>
        Area: {{ $history->selected_location ?? 'Gunung Misterius' }}
    </div>
    
    @if(isset($storyPages) && count($storyPages) > 0)
        @foreach($storyPages as $index => $page)
            <div class="story-page">
                <div class="page-layout">
                    @if(isset($page['image_base64']) && $page['image_base64'])
                    <div class="image-container">
                        <img src="{{ $page['image_base64'] }}" class="story-image" alt="Scene {{ $index + 1 }}">
                        <div class="chapter-label">Bagian {{ $index + 1 }}</div>
                    </div>
                    @elseif(isset($page['image']) && $page['image'])
                    <div class="image-container">
                        <img src="{{ $page['image'] }}" class="story-image" alt="Scene {{ $index + 1 }}">
                        <div class="chapter-label">Bagian {{ $index + 1 }}</div>
                    </div>
                    @endif
                    
                    <div class="text-column">
                        <div class="story-text">
                            {!! nl2br(e($page['content'])) !!}
                        </div>
                        
                        @if(isset($page['choice']) && $page['choice'])
                            <div style="margin-top: 20px; font-style: italic; color: #8b0000; text-align: center;">
                                >> Pilihan yang diambil: "{{ $page['choice'] }}"
                            </div>
                        @endif
                    </div>
                </div>
                <div class="page-number">
                    Halaman {{ $index + 1 }} / {{ count($storyPages) }}
                </div>
            </div>
        @endforeach
    @else
        <!-- Fallback jika array kosong -->
        <div class="story-text">
            {!! nl2br(e($history->accumulated_story)) !!}
        </div>
    @endif
    
    <div class="footer">
        --- TAMAT ---<br>
        Tak ada yang bisa kembali.
    </div>
</div>
</body>
</html>