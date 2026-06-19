# LAPORAN RESUME KONTRIBUSI TIM & INDIVIDU
### TUGAS BESAR INTEGRASI APLIKASI ENTERPRISE (IAE)

- **Nama:** RADEN FATIR PAUNDRAYUDHA AIRLANGGA AFFANDHI
- **NIM:** 102022430058
- **Kelas:** SI-48-09
- **Peran:** Pembuat Service B (Procurement)
- **Kelompok:** 03

## 1. Ahmad Rizky I. (102022400004) - Service A (Inventory)
*Melakukan perancangan dan implementasi Service A lewat branch  `rizky-service-a`.*

1. **Pengembangan Database & Model**: Membangun skema database MySQL lokal, merancang migrasi untuk tabel `components`, serta membuat struktur model dan controller inventori.
2. **Autentikasi Keamanan**: Mengintegrasikan sistem verifikasi Single Sign-On (SSO) berbasis JSON Web Token (JWT) pada endpoint Service A.
3. **Integrasi SOAP Audit**: Mengembangkan SOAP XML Client untuk mengirimkan data aktivitas *Receive Stock* ke sistem audit eksternal serta memproses penyimpanan data `ReceiptNumber`.
4. **Event-Driven Messaging**: Mengimplementasikan AMQP Publisher untuk menyebarkan pesan/event `component.received` ke broker RabbitMQ saat stok baru terdata.
5. **Integrasi Lingkungan Sistem**:
  - Mengelola penggabungan (merge) branch fungsional (`rizky-service-a`, `fatir-service-b`, dan `rizwan-service-c`) ke dalam repositori utama kelompok.
  - Menyusun file konfigurasi `docker-compose.yml` untuk memanifestasikan seluruh service ke dalam container.
  - Mengonfigurasi Nginx API Gateway untuk single entry point.
  - Melakukan pengujian untuk memastikan komunikasi antar-service berjalan lancar di Docker.
  

## 2. Raden Fatir Paundrayudha Airlangga Affandhi (102022430058) - Service B (Procurement)
*Melakukan perancangan dan implementasi Service B lewat branch  `fatir-service-b`.*

1. **Basis Data Lokal**: Merancang skema penyimpanan database SQLite beserta pembuatan tabel `procurements` dan `procurement_items`.
2. **Fitur Utama API**: Membangun antarmuka API CRUD yang lengkap dan fleksibel untuk mengelola siklus data Purchase Order (PO).
3. **Keamanan Endpoint**: Menerapkan validasi keamanan Machine-to-Machine (M2M) dengan server SSO menggunakan metode API Key.
4. **Integrasi SOAP Audit**: Mengembangkan SOAP Client untuk memvalidasi kecocokan Purchase Order dan mengamankan penyimpanan `soap_receipt_number` untuk tanda bukti transaksi.
5. **Event-Driven Messaging**: Memprogram fungsionalitas AMQP Publisher untuk menerbitkan event `procurement.created` ke RabbitMQ ketika dokumen PO berhasil dibuat.
6. **Integrasi Lintas Service**:
  - Membuat endpoint API integrasi untuk menangani pembaruan status Purchase Order dari sistem gudang eksternal.
  - Melakukan debugging dan finalisasi Service B sebelum kode digabungkan ke branch utama.
  - Melakukan perbaikan payload request otentikasi SSO M2M dengan menambahkan parameter NIM untuk memenuhi instruksi teknis terbaru dari dosen.
  

## 3. Rizwan Saputra (102022430048) - Service C (Expedition)
*Melakukan perancangan dan implementasi Service C lewat branch  `rizwan-service-c`.*

1. **Layanan Logistik**: Membangun REST API fungsional untuk mendokumentasikan data manifest pengiriman logistik masuk (`inbound-shipments`).
2. **Autentikasi Keamanan**: Mengembangkan middleware `VerifyJwtSso` untuk memvalidasi kecocokan token JWT secara dinamis menggunakan JWKS dari server SSO.
3. **Integrasi SOAP Audit**: Menyusun SOAP Client untuk melakukan pendaftaran manifest ke sistem audit eksternal sekaligus menyimpan `legacy_receipt_number` untuk bukti integrasi.
4. **Event-Driven Messaging**: Membuat modul AMQP Publisher untuk mengirimkan informasi `shipment.created` via RabbitMQ saat ekspedisi siap diproses.
5. **Finalisasi & Refactoring**:
  - Menyelesaikan proses integrasi internal Service C secara mandiri sebelum digabungkan ke repositori utama kelompok.
  - Meningkatkan fungsi parser token JWT dan melakukan update pada pencatatan log aktivitas integrasi service.
  - Melakukan penyesuaian fungsionalitas request M2M ke SSO sesuai instruksi terbaru dari dosen pengampu.
