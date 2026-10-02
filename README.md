# Bapelkes Jateng

Tema WordPress kustom untuk situs Balai Pelatihan Kesehatan Provinsi Jawa Tengah. Desainnya berasal dari Figma (14 screen desktop 1440px, ditambah penyesuaian mobile). Repo ini berisi tema saja. WordPress, database, dan konten tidak ikut tersimpan di sini.

Pratinjau statis: https://dev-bapelkes-jateng.xfhreall.workers.dev (isinya data contoh, bukan data resmi Bapelkes).

## Kebutuhan

- PHP 8.0 atau lebih baru, dengan ekstensi `pdo_sqlite`, `mbstring`, `xml`, `curl`. Cek SQLite dengan `php -m | grep -i sqlite`.
- WordPress 6.5 atau lebih baru (diunduh oleh skrip di bawah)
- [WP-CLI](https://wp-cli.org/), `git`, `curl`, `unzip`
- Database: SQLite lewat plugin `sqlite-database-integration` (dipakai di bawah, tanpa service database), atau MySQL/MariaDB biasa

Sistem operasi:

| OS | Cara |
|---|---|
| Linux | Jalankan skrip di bawah. |
| macOS | Pasang `brew install php wp-cli git`, lalu jalankan skrip di bawah. |
| Windows | Pakai [WSL2](https://learn.microsoft.com/windows/wsl/install) (Ubuntu) dan jalankan semua di terminal WSL. WP-CLI tidak mendukung Windows secara resmi, dan skrip di bawah berupa bash. Tanpa WSL, ikuti jalur manual di bawah skrip. |

Skrip sudah diuji penuh di Linux (Fedora, PHP 8.5, WP-CLI 2.12). macOS dan WSL memakai perintah yang sama, tetapi belum diuji langsung.

## Pasang lingkungan lokal

Langkah di bawah memakai SQLite. Kalau Anda memilih MySQL/MariaDB, ganti langkah 2 dengan membuat database kosong, isi kredensial aslinya di `wp config create` (tanpa `--skip-check`), dan lanjut ke langkah 3.

```bash
mkdir bapelkes && cd bapelkes

# 1. WordPress dan wp-config
wp core download
wp config create --dbname=wp_dev --dbuser=wp --dbpass=wp --skip-check
wp config set BAPELKES_DEMO true --raw

# 2. SQLite (diunduh langsung; "wp plugin install" butuh database yang belum ada)
curl -sSL -o sqlite.zip https://downloads.wordpress.org/plugin/sqlite-database-integration.zip
unzip -q sqlite.zip -d wp-content/plugins && rm sqlite.zip
cp wp-content/plugins/sqlite-database-integration/db.copy wp-content/db.php

# 3. Tema
git clone https://github.com/Xfhreall/bapelkes-jateng.git wp-content/themes/bapelkes-jateng

# 4. Instalasi, tema, permalink
wp core install --url=http://127.0.0.1:8080 --title="Bapelkes Jateng" \
  --admin_user=admin --admin_password=admin --admin_email=dev@example.com
wp theme activate bapelkes-jateng
wp rewrite structure '/%postname%/' --hard

# 5. Halaman
while IFS=: read -r slug judul; do
  wp post create --post_type=page --post_status=publish --post_name="$slug" --post_title="$judul"
done <<'EOF'
beranda:Beranda
publikasi:Publikasi
profil:Profil
layanan:Layanan
fasilitas:Fasilitas
galeri:Galeri
unduhan:Unduhan
suara-pembaca:Suara Pembaca
pelayanan-publik:Pelayanan Publik
standar-pelayanan:Standar Pelayanan Pelatihan
EOF
wp option update show_on_front page
wp option update page_on_front "$(wp post list --post_type=page --name=beranda --field=ID)"
wp option update page_for_posts "$(wp post list --post_type=page --name=publikasi --field=ID)"

# 6. Jalankan
wp server --host=127.0.0.1 --port=8080
```

Buka http://127.0.0.1:8080. Login admin di `/wp-login.php` dengan `admin` / `admin` (khusus lokal, jangan pakai di server publik).

Jalur manual tanpa WP-CLI (Windows tanpa WSL): pasang WordPress lewat [Laragon](https://laragon.org/) atau [LocalWP](https://localwp.com/), clone repo ini ke folder `wp-content/themes/bapelkes-jateng`, lalu aktifkan tema di Appearance > Themes. Setelah itu:

1. Settings > Permalinks: pilih "Post name".
2. Buat 10 halaman dengan judul dan slug persis seperti daftar di langkah 5.
3. Settings > Reading: halaman depan "Beranda", halaman posting "Publikasi".
4. Tambahkan konstanta `define( 'BAPELKES_DEMO', true );` di `wp-config.php` bila ingin pita pratinjau.

Masalah yang sering muncul:

- `Allowed memory size ... exhausted` saat `wp core download`: batas memori CLI PHP (bawaan 128M) terlalu kecil. Jalankan WP-CLI dengan `php -d memory_limit=512M $(which wp) core download`, atau naikkan `memory_limit` di `php.ini` CLI.
- `Error establishing a database connection` saat langkah 2: `db.php` belum tersalin, atau ekstensi `pdo_sqlite` belum terpasang.
- Peringatan `sendmail: No such file` saat `wp core install` dan peringatan `.htaccess` saat `wp rewrite`: abaikan.
- Dengan SQLite, `wp db query` selalu error karena WP-CLI memanggil klien MariaDB. Abaikan.
- `/pelatihan/` memberi 404: tipe konten itu sengaja tanpa halaman arsip. Daftar pelatihan ada di `/layanan/`.

## Struktur

```
style.css              Semua CSS (token desain dari Figma ada di :root)
functions.php          Setup tema, enqueue aset, menu bawaan, data tiga kampus
front-page.php         Beranda
home.php, archive.php  Publikasi (daftar berita) dan arsip kategori
single.php             Detail berita
single-pelatihan.php   Detail satu pelatihan
taxonomy-kampus.php    Fasilitas per kampus
page-<slug>.php        Satu template per halaman statis; WordPress memilihnya dari slug
inc/                   Tipe konten, field halaman, form, rute
template-parts/        Potongan yang dipakai banyak template
assets/                img, icons, js
demo/                  Gambar contoh untuk mengisi berita dan galeri lokal
```

Isi `inc/`:

| Berkas | Fungsi |
|---|---|
| `pelatihan.php` | Tipe konten `pelatihan`, taksonomi `kampus`, meta tanggal |
| `fasilitas.php` | Tipe konten `fasilitas`, taksonomi `kelompok` |
| `masukan.php` | Tipe konten `masukan` dan penanganan form Suara Pembaca |
| `konten-halaman.php` | Kotak "Konten Halaman" di editor, plus tipe konten `pimpinan` |
| `rute.php` | Rewrite rule path, misalnya `/layanan/2026-10/` |

## Cara kerja yang perlu Anda tahu

**Halaman dicocokkan lewat slug.** `page-profil.php` hanya terpakai bila ada halaman berslug `profil`. Langkah 5 di atas membuat semuanya. Slug yang salah membuat halaman jatuh ke `index.php`.

**Teks halaman statis tidak ditulis di template.** Halaman Profil, Fasilitas, dan Pelayanan Publik punya tata letak tetap, tetapi teksnya diisi admin lewat kotak "Konten Halaman" di editor halaman. Daftar field ada di `bapelkes_field_halaman()` pada `inc/konten-halaman.php`. Template memanggil `bapelkes_konten()`, yang memakai teks bawaan desain bila field kosong. Untuk menambah field, tambahkan baris di fungsi itu dan panggil `bapelkes_konten()` di template.

**Rute berbasis path.** Hosting statis mengabaikan query string, jadi `?bulan=2026-10` selalu menghasilkan halaman yang sama. `inc/rute.php` memakai `/layanan/2026-10/`, `/unduhan/2026-10/`, `/profil/dokumen/3/`, dan `/pelatihan/<slug>/hal/2/`. Setelah mengubah rewrite rule, jalankan `wp rewrite flush`.

**Penanda demo.** Konstanta `BAPELKES_DEMO` di `wp-config.php` menampilkan pita "pratinjau" di atas situs. Hapus konstanta itu pada instalasi produksi.

**Data kampus** (Gombong, Wonosobo, Ungaran) berupa array statis di `bapelkes_kampus()` dalam `functions.php`. Alamat dan foto kampus diubah di sana.

**Konten demo.** Berita, galeri, sertifikat, dan foto kampus yang Anda lihat di lokal hanya data contoh yang dimasukkan lewat admin. Berkas gambar contoh ada di `demo/`. Konten ini tidak masuk database repo dan tidak untuk produksi.

## Mengerjakan perubahan

1. Buat cabang: `git switch -c feat/nama-fitur`.
2. Ubah kode, lalu cek hasilnya di browser pada lebar desktop (1440px) dan mobile.
3. Bandingkan dengan desain Figma. Bila Anda perlu akses file desain, minta pemilik repo.
4. Commit memakai awalan `feat:`, `fix:`, atau `perf:`, seperti riwayat yang sudah ada.
5. Buka pull request ke `main`.

## Deploy

Hosting produksi belum diputuskan. Situs `xfhreall.wordpress.com` memakai paket Free, yang tidak bisa mengunggah tema. Tema ini perlu WordPress self-hosted atau paket WordPress.com yang mengizinkan tema kustom.

Pratinjau di Cloudflare Workers berupa hasil rayapan statis dari WordPress lokal. Pembuatnya ada di mesin pemilik repo, tidak di repo ini.

## Lisensi

GPL v2 atau lebih baru, sesuai header `style.css`.
