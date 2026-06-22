# Fallback Story Archive Feature Documentation

## Overview
Program sekarang dapat mendeteksi kondisi koneksi/token lemah dan secara otomatis menggunakan cerita dari arsip daripada menampilkan error kepada user.

## Fitur-Fitur Utama

### 1. **Deteksi Kondisi Jaringan & API**
- **Timeout**: API tidak merespons dalam 10 detik
- **Token Exhausted (429)**: Quota token Gemini telah habis
- **Invalid Token (401)**: Token API tidak valid
- **Network Error**: Koneksi internet lemot/tidak stabil
- **API Error**: Error lainnya dari server API

### 2. **Story Archive System**
Tabel `story_archives` menyimpan:
- `genre`: Kategori cerita (Horror, Adventure, dll)
- `location`: Lokasi dalam cerita (Pendakian, Rumah Sakit, dll)
- `node_content`: Teks cerita yang sudah dihasilkan AI
- `choices_json`: Pilihan yang tersedia
- `image_url`: Gambar yang terkait
- `usage_count`: Berapa kali cerita ini digunakan dari arsip
- Index pada `(genre, location)` untuk query cepat

### 3. **Tracking & Reporting**
Field baru di tabel `user_histories`:
- `generation_mode`: 'realtime' (dari AI) atau 'archive' (dari arsip)
- `api_response_time`: Response time API dalam milliseconds (null jika archive)
- `fallback_reason`: Alasan fallback (timeout, token_error, network_error, api_error)

### 4. **Flow Keseluruhan**

```
generateInitialStory() / generateNextNode()
    ↓
    callAI() dengan timeout 10 detik
    ├─ SUCCESS → return dengan generation_mode='realtime'
    │           save ke database & archive
    ├─ TIMEOUT → fallback_reason='timeout'
    ├─ TOKEN ERROR (429/401) → fallback_reason='token_error'
    ├─ NETWORK ERROR → fallback_reason='network_error'
    └─ API ERROR → fallback_reason='api_error'
    ↓
    Jika ada fallback_reason:
    ├─ Query random dari `story_archives`
    │  WHERE genre = ? AND location = ?
    ├─ Ambil 1 dari hasil, increment usage_count
    └─ return dengan generation_mode='archive'
```

## Implementasi

### Model: `app/Models/StoryArchive.php`
```php
- getRandomByGenreAndLocation($genre, $location): single record
- getRandomMultipleByGenreAndLocation($genre, $location, $limit): multiple records
- incrementUsageCount(): increment counter
```

### Service: `app/Services/StoryGenerator.php`

#### Method `callAI()` - Enhanced
- Track response time: `microtime(true)` 
- Return struktur dengan:
  - `generation_mode`: 'realtime' atau 'archive'
  - `api_response_time`: ms (atau null untuk non-API)
  - `fallback_reason`: specific reason jika gagal
- Detect error types (timeout, 429, 401, connection, etc)

#### Method `saveToArchive($genre, $location, $content, $choices, $imageUrl)`
- Simpan successful generation ke archive
- Avoid duplicates dengan check existing

#### Method `getArchiveFallback($genre, $location)`
- Get random dari archive untuk fallback

### Livewire: `app/Livewire/GameEngine.php`

#### Method `startStory()`
- Capture generation_mode, api_response_time, fallback_reason
- Save ke user_histories
- Call saveToArchive jika realtime

#### Method `selectChoice()`
- Pass genre & location ke generateNextNode
- Track semua metadata untuk reporting
- Save ke archive jika realtime

## Database Migrations

### Migration 1: `create_story_archives_table.php`
```
- id (primary key)
- genre, location (indexed)
- node_content (longtext)
- choices_json (json)
- image_url (nullable)
- usage_count (default 0)
- timestamps
```

### Migration 2: `add_generation_fields_to_user_histories.php`
```
- generation_mode (enum: realtime, archive)
- api_response_time (integer, nullable)
- fallback_reason (enum: timeout, token_error, network_error, api_error, nullable)
```

## Reporting / Laporan

Untuk menghasilkan laporan, query dari `user_histories`:

### Query Contoh 1: Statistik Mode Generasi
```sql
SELECT 
  generation_mode,
  COUNT(*) as total,
  ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM user_histories), 2) as percentage
FROM user_histories
WHERE is_finished = 1
GROUP BY generation_mode;
```

### Query Contoh 2: Breakdown Fallback Reason
```sql
SELECT 
  fallback_reason,
  COUNT(*) as count,
  ROUND(AVG(api_response_time), 2) as avg_response_time_ms
FROM user_histories
WHERE generation_mode = 'archive' AND fallback_reason IS NOT NULL
GROUP BY fallback_reason;
```

### Query Contoh 3: Archive Usage
```sql
SELECT 
  genre,
  location,
  COUNT(*) as usage_count
FROM story_archives
WHERE usage_count > 0
GROUP BY genre, location
ORDER BY usage_count DESC;
```

## Laporan Template

```
LAPORAN PERFORMA GENERASI CERITA

Periode: [Tanggal Mulai] - [Tanggal Akhir]

1. STATISTIK KESELURUHAN
   - Total Pemain Selesai: X orang
   - Total Cerita Dihasilkan: Y node

2. MODE GENERASI
   ┌─────────────┬────────┬──────────┐
   │ Mode        │ Total  │ Persen   │
   ├─────────────┼────────┼──────────┤
   │ Realtime AI │ X node │ XX%      │
   │ Dari Arsip  │ Y node │ YY%      │
   └─────────────┴────────┴──────────┘

3. KONDISI FALLBACK (saat API tidak lancar)
   ┌─────────────────┬────────┬──────────────────┐
   │ Penyebab        │ Count  │ Avg Response(ms) │
   ├─────────────────┼────────┼──────────────────┤
   │ Timeout         │ X      │ XXX ms           │
   │ Token Habis     │ Y      │ N/A              │
   │ Network Error   │ Z      │ N/A              │
   │ API Error       │ A      │ N/A              │
   └─────────────────┴────────┴──────────────────┘

4. PERFORMA API
   - Rata-rata Response Time: XXX ms
   - Success Rate: XX%
   - P95 Response Time: XXX ms

5. ARSIP CERITA
   ┌──────────┬──────────────┬──────────┐
   │ Genre    │ Lokasi       │ Dipakai  │
   ├──────────┼──────────────┼──────────┤
   │ Horror   │ Pendakian    │ XX kali  │
   │ Horror   │ Rumah Sakit  │ YY kali  │
   │ Adventure│ Pulau Terpencil │ ZZ kali  │
   └──────────┴──────────────┴──────────┘

KESIMPULAN:
- Program siap untuk kondisi lancar (token ready, internet lancar)
  dengan menampilkan cerita real-time dari AI
- Program juga disiapkan untuk kondisi tidak lancar (token abis, internet lemot)
  dengan fallback ke arsip cerita yang sudah dihasilkan sebelumnya
```

## Testing

### Test Scenario 1: Kondisi Lancar
- Setup: Network normal, API token cukup
- Ekspektasi: generation_mode = 'realtime' untuk semua node
- Verify: full_story_json punya 'generation_mode': 'realtime'

### Test Scenario 2: Timeout Simulation
- Setup: Ubah timeout di StoryGenerator menjadi 1 detik
- Ubah API_URL ke endpoint yang lambat (simulasi timeout)
- Ekspektasi: fallback_reason = 'timeout', generation_mode = 'archive'

### Test Scenario 3: Token Exhausted
- Setup: Mock HTTP response dengan status 429
- Ekspektasi: fallback_reason = 'token_error', generate dari arsip

### Test Scenario 4: No Archive Available
- Setup: kosongkan table story_archives untuk genre tertentu
- Ekspektasi: Error graceful, user lihat pesan "Arsip cerita tidak tersedia"

## Next Steps

1. **Run Migrations**
   ```bash
   php artisan migrate
   ```

2. **Populate Initial Archive** (optional)
   - Jalankan game beberapa kali dalam kondisi lancar
   - Archive otomatis terisi dari successful generations
   - Atau buat seeder untuk populate manual

3. **Monitor & Adjust**
   - Monitor usage_count di story_archives
   - Jika ada cerita yang tidak pernah dipake dari arsip, bisa dihapus
   - Adjust timeout threshold jika diperlukan (sekarang 10 detik)

4. **Generate Reports**
   - Build dashboard untuk tracking generation_mode
   - Query dan visualisasi data untuk laporan final
