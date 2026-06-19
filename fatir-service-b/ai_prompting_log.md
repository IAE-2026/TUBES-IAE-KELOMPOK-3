# Rekap Log Prompting AI
## Tugas 2, Tugas 3, Tugas Besar IAE

**Nama** : Raden Fatir Paundrayudha Airlangga Affandhi

**NIM** : 102022430058

**Service** : Service B - Procurement (Pengadaan Bahan Baku)

**AI Engine** : Claude dan Google Gemini

#### Daftar Prompt User:

1. **[15 Mei 2026, 20:47]**
    > "1. Judul Utama Proses Bisnis
      Alur Pemenuhan Bahan Baku Pabrik Elektronik (Inbound Supply Chain)

      2. Sub-Process
      * Sub-Proses 1: Pengecekan Stok Komponen (Dipegang oleh Service A - Gudang Bahan Baku)
      * Sub-Proses 2: Pengadaan Bahan Baku / Procurement (Dipegang oleh Service B - Pembelian/Procurement)
      * Sub-Proses 3: Logistik Penerimaan Masuk / Inbound Logistics (Dipegang oleh Service C - Ekspedisi/Logistik\

      3. Alur Activity Masing-Masing Subprocess
      Sub-Proses 1: Pengecekan Stok Komponen
      * Melihat daftar katalog bahan baku atau komponen elektronik yang tersedia di gudang penyimpanan utama pabrik.
      * Memeriksa detail spesifikasi dan batas minimum stok pada satu komponen tertentu (misalnya mengecek stok IC Mikrokontroler untuk melihat apakah sudah waktunya re-stock).
      * Mencatat penambahan jumlah komponen (Goods Receipt) secara otomatis ke dalam sistem ketika bahan baku dari supplier sudah tiba dan lolos Quality Control.
      Sub-Proses 2: Pengadaan Bahan Baku (Procurement)
      * Membuat Purchase Order (PO) atau dokumen pemesanan baru yang akan dikirimkan ke pihak supplier komponen elektronik.
      * Melihat rincian satu pesanan Purchase Order untuk mengetahui kelengkapannya (seperti daftar part numberkomponen yang dipesan dan total harganya).
      * Melihat daftar riwayat seluruh pengadaan bahan baku yang pernah diajukan oleh pabrik ke berbagai supplier.
      Sub-Proses 3: Logistik Penerimaan Masuk (Inbound Logistics)
      * Menerima jadwal pengiriman dari supplier dan menerbitkan nomor pelacakan (resi/manifest) untuk kontainer logistik yang masuk ke pabrik.
      * Melacak status perjalanan truk kargo atau kontainer yang membawa komponen elektronik berdasarkan nomor pelacakan secara spesifik.
      * Melihat daftar rekapitulasi seluruh armada logistik yang sedang dalam perjalanan menuju pabrik.

      4. Mapping Service & Endpoint Berdasarkan Alur End-to-End
      Tahap 1: Pabrik Mengaudit Stok Komponen (Service A) Sistem gudang atau tim produksi mengecek komponen elektronik apa saja yang stoknya sudah menipis dan perlu dipesan ulang.
      * Service A (Gudang Bahan Baku)
         * GET /api/v1/components → Mengambil daftar seluruh bahan baku/komponen elektronik.
      GET /api/v1/components/{id} → Mengambil detail spesifikasi dan sisa stok satu komponen spesifik.


      Tahap 2: Pabrik Menerbitkan Purchase Order ke Supplier (Service B) Karena stok IC Mikrokontroler menipis, tim Procurement membuat pesanan bahan baku ke pabrikan supplier.
      * Service B (Pembelian/Procurement)
         * POST /api/v1/procurements → Membuat dokumen Purchase Order (PO) baru untuk dikirim ke supplier.


      Tahap 3: Pemantauan Riwayat Pemesanan (Service B) Manajer pabrik memantau daftar pesanan yang sedang diajukan ke supplier.
      * Service B (Pembelian/Procurement)
         * GET /api/v1/procurements → Mengambil daftar seluruh riwayat Purchase Order.
      GET /api/v1/procurements/{id} → Mengambil detail kelengkapan dari satu Purchase Order.


      Tahap 4: Supplier Mengirimkan Bahan Baku (Service C) Pihak supplier menyetujui pesanan dan mendaftarkan kargo mereka ke sistem logistik masuk pabrik.
      * Service C (Ekspedisi/Logistik)
         * POST /api/v1/inbound-shipments → Menerima data manifest pengiriman dari supplier dan menerbitkan jadwal/nomor pelacakan.


      Tahap 5: Pabrik Melacak Perjalanan Kargo (Service C) Tim supply chain pabrik mengecek estimasi kedatangan komponen agar lini produksi bisa bersiap.
      * Service C (Ekspedisi/Logistik)
         * GET /api/v1/inbound-shipments → Mengambil daftar keseluruhan truk/kargo yang sedang menuju pabrik.
      GET /api/v1/inbound-shipments/{id} → Melacak status dan posisi spesifik dari satu truk/kargo berdasarkan nomor resinya.


      Tahap 6: Komponen Tiba & Penambahan Stok (Service A) Kargo tiba di pabrik. Setelah dibongkar dan dicek kelayakannya, tim gudang memasukkan barang ke inventori sehingga siap dipakai oleh lini produksi.
      * Service A (Gudang Bahan Baku)
    * POST /api/v1/components/receive → Aksi mencatat penerimaan barang sekaligus menambah jumlah stok komponen di database pabrik.

    Der, diatas gua kasih rincian proses bisnis gua yang mau gua kerjakan sesuai instruksi penugasan tugas 2 IAE. gua mau lu jelaskan lebih lanjut mengenai service b order dan apa saja fungsinya serta bagaimana cara kerja nya"

2. **[15 Mei 2026, 20:51]**
   > "1. Judul Utama Proses Bisnis: Alur Pemenuhan Bahan Baku Pabrik Elektronik (Inbound Supply Chain)... bagian service gua (service b order) ini apa saja step yang harus gua ikuti agar pengerjaan gua sesuai dengan ketentuan tugas?"

3. **[15 Mei 2026, 20:56]**
   > "penugasan cukup backend tidak perlu sampai ke pembuatan frontend dashboard, lanjut untuk Konfigurasi API_KEY menggunakan NIM gua yaitu 102022430058 dan Menjalankan Swagger Generator"

4. **[15 Mei 2026, 21:05]**
   > "Untuk bagian swagger tampilannya belum sesuai dan kurang tepat, gua mau lu perbaiki menggunakan endpoint ini: POST /api/v1/procurements : Membuat PO baru (Tahap 2). GET /api/v1/procurements : Melihat riwayat seluruh PO (Tahap 3). GET /api/v1/procurements/{id} : Melihat rincian satu PO spesifik (Tahap 3). ada 3 dan bisa di try it out dan masukan authorize nim"

5. **[15 Mei 2026, 21:24]**
   > "lanjut untuk pengerjaan file graphql playground pada web browser, gua ingin anda menjelaskan step-step nya"

6. **[15 Mei 2026, 21:31]**
   >  gua ingin lu cek progress pengerjaan gua seperti ini. apakah pengerjaan tugas nya sudah sesuai ketentuan dan rubrik penilainnya apakah sesuai dan sudah terpenuhi juga?"

7. **[15 Mei 2026, 21:33]**
   > "apakah berarti ini sudah lengkap pengerjaan nya ?"

8. **[15 Mei 2026, 21:39]**
    > "untuk port nya, ganti 'port' di docker-compose.yml dan juga di .env nya menjadi ports: '8001:80'"

9. **[15 Mei 2026, 21:42]**
    > "Standard Integration Contract (IAE-T2)
   Setiap service individu wajib mematuhi standar teknis berikut agar dapat
   berinteraksi dalam ekosistem Enterprise:
   1. Protokol & Format Data
   Protokol: HTTP/1.1
   Format Pesan: JSON (JavaScript Object Notation)
   Charset: UTF-8
   Content-Type: application/json
   2. Standar Struktur Respon (Wrapper)
   Semua API yang dibuat wajib membungkus (wrap) data dalam struktur yang
   konsisten agar mudah diproses oleh sistem lain:
   Respon Berhasil (Success - 2xx):
   JSON
   {
   "status": "success",
   "message": "Data retrieved successfully",
   "data": { ... }, // Objek atau Array data utama
   "meta": { // Opsional: Untuk pagination atau info
   tambahan
   "service_name": "Inventory-Service",
   "api_version": "v1"
   }
   }
   Respon Gagal (Error - 4xx/5xx):
   JSON
   {
   "status": "error",
   "message": "Detail pesan kesalahan (misal: Resource not
   found)",
   "errors": null // Opsional: Detail error validasi (array)
   }
   3. Keamanan (X-IAE-KEY)
   Setiap endpoint harus diproteksi dengan API Key. Untuk Tugas 2, mahasiswa
   menggunakan mekanisme Header Authentication:
   Header Key: X-IAE-KEY
   Value: [NIM Mahasiswa] (Sebagai identitas sementara sebelum pindah
   ke SSO di Tugas 3).
   4. Spesifikasi Endpoint (Minimum Viable API)
   Setiap layanan wajib menyediakan minimal 3 jenis akses:
   Collection: GET /api/v1/[resource] (Mengambil daftar data).
   Resource: GET /api/v1/[resource]/{id} (Mengambil data spesifik).
   Action: POST /api/v1/[resource] (Menambah data baru/memicu
   proses).
   apakah standar penugasan untuk service b order sudah sesuai ketentuan semua? coba identifikasi dan beri tahu gua jika ada yang belum dan deskripsi nya"

10. **[15 Mei 2026, 21:45]**
    >  gua pakai docker desktop dan harus buat dockerfile. tolong buatkan isi dockerfile nya sesuai dengan ketentuan tugas dan ada docker-compose.yml juga. dan perbaiki juga port nya menjadi 8001"

11. **[15 Mei 2026, 21:50]**
    > "apakah pengerjaan tugas nya sudah sesuai ketentuan dan rubrik penilaian nya apakah sudah terpenuhi juga? kalau belum, daftarkan apa saja kekurangan dan evaluasi dari pengerjaan nya."

12. **[12 Juni 2026, 13:35]**
    > "gua mau ngerjain Tugas 3 IAE untuk Service B - Procurement dari awal. ketentuan nya adalah mengintegrasikan SSO Dosen (autentikasi JWT token), SOAP Audit untuk melacak transaksi kritis pembuatan PO ke sistem pusat, dan RabbitMQ untuk menyiarkan pesan ke service lain. tolong jelaskan analisis alur integrasi end-to-end, kebutuhan database, dan apa saja langkah pertama yang harus kita buat."

13. **[12 Juni 2026, 14:15]**
    > "oke, sekarang kita mulai implementasi foundation-nya. pertama, install library php-jwt untuk verifikasi token JWT. lalu buatkan SsoService di Laravel untuk mengambil JWKS (public key) dari SSO Dosen, memverifikasi token JWT, dan memetakan peran (role) dari token ke database lokal (misal: 'user' jadi peran 'warga', dan 'm2m' jadi peran 'm2m')."

14. **[12 Juni 2026, 15:00]**
    > "lanjut, buatkan SOAP Client Service untuk pelaporan transaksi kritis. ketika PO dibuat, sistem harus memanggil SSO untuk mendapatkan token M2M terlebih dahulu, lalu mengirim request XML SOAP ke `/soap/v1/audit` milik Dosen untuk mencatat detail PO. hasil dari SOAP audit berupa ReceiptNumber harus kita simpan di database lokal. buatkan kode PHP/Laravel untuk service ini."

15. **[12 Juni 2026, 15:40]**
    > "sekarang buatkan AmqpPublisherService. setelah PO berhasil disimpan dan dilaporkan lewat SOAP, kita harus mempublikasikan pesan JSON berisi detail PO ke RabbitMQ melalui HTTP Gateway `/api/v1/messages/publish`. Gunakan exchange 'iae.central.exchange' dan routing key 'procurement.created'."

16. **[12 Juni 2026, 16:15]**
    > "gua butuh migrasi database untuk mendukung struktur data baru ini. buatkan 3 file migrasi di Laravel: satu untuk membuat tabel 'roles', kedua untuk menambahkan 'role_id' ke tabel 'users', dan ketiga untuk menambahkan kolom 'soap_receipt_number' di tabel 'procurements'. update juga Model User, Role, dan Procurement agar relasinya terdefinisi dengan baik."

17. **[12 Juni 2026, 16:50]**
    > "sekarang buat middleware untuk autentikasi. middleware ini harus mendukung dua skema: API Key lama (X-IAE-KEY menggunakan NIM) dan Bearer Token SSO Dosen yang baru. Setelah itu, update ProcurementController bagian 'store' agar mengintegrasikan semua langkah: verifikasi token SSO user, simpan PO ke database lokal, kirim laporan ke SOAP Audit dengan token M2M, dapatkan receipt number, simpan receipt number ke PO, lalu publish detail PO ke RabbitMQ, dan kembalikan respons sukses berformat standar."

18. **[12 Juni 2026, 17:35]**
    > "sekarang buatkan isi kodingan Dockerfile dan update isi kodingan docker-compose.yml nya agar bisa berjalan di port 8001."

19. **[18 Juni 2026, 19:10]**
    > "gua mau ubah untuk di bagian SOAP Client agar bisa menyimpan soap_receipt_number."

20. **[18 Juni 2026, 19:20]**
    > "gua mau tambahin buat AMQP Publisher agar bisa mengirim event procurement.created."

21. **[18 Juni 2026, 19:30]**
    > "gua mau tambahin 1 endpoint baru agar bisa berkomunikasi dengan service a dan c secara end to end."
