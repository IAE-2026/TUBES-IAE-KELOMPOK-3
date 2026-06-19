# LAPORAN RESUME KONTRIBUSI TIM & INDIVIDU
### TUGAS BESAR INTER-APP ENGAGEMENT (IAE)

**Identitas Mahasiswa:**
- **Nama:** RADEN FATIR PAUNDRAYUDHA AIRLANGGA AFFANDHI
- **NIM:** 102022430058
- **Kelas:** SI-48-09
- **Peran:** Developer Service B (Procurement)
- **Kelompok:** 03

---

## 1. Ahmad Rizky (102022400004) - Service A (Inventory)
*Melakukan perancangan dan implementasi Service A melalui branch khusus `rizky-service-a`.*

* **Pengembangan Database & Model**: Membangun skema database MySQL lokal, merancang migrasi untuk tabel `components`, serta membuat struktur model dan controller inventori.
* **Autentikasi Keamanan**: Mengintegrasikan sistem verifikasi Single Sign-On (SSO) berbasis JSON Web Token (JWT) pada endpoint Service A.
* **Integrasi SOAP Audit**: Mengembangkan SOAP XML Client untuk mengirimkan data aktivitas *Receive Stock* ke sistem audit eksternal serta memproses penyimpanan data `ReceiptNumber`.
* **Event-Driven Messaging**: Mengimplementasikan AMQP Publisher untuk menyebarkan pesan/event `component.received` ke broker RabbitMQ saat stok baru terdata.
* **Integrasi Lingkungan Sistem**:
  - Mengelola penggabungan (merge) branch fungsional (`rizky-service-a`, `fatir-service-b`, dan `rizwan-service-c`) ke dalam repositori utama kelompok.
  - Menyusun file konfigurasi `docker-compose.yml` untuk memanifestasikan seluruh service ke dalam container terpadu.
  - Mengonfigurasi Nginx API Gateway sebagai gerbang tunggal pengatur rute request (single entry point).
  - Melakukan pengujian menyeluruh guna memastikan komunikasi antar-service berjalan lancar di lingkungan Docker.

---

## 2. RADEN FATIR PAUNDRAYUDHA AIRLANGGA AFFANDHI (102022430058) - Service B (Procurement)
*Melakukan perancangan dan implementasi Service B melalui branch khusus `fatir-service-b`.*

* **Basis Data Lokal**: Merancang skema penyimpanan database SQLite beserta pembuatan tabel `procurements` dan `procurement_items`.
* **Fitur Utama API**: Membangun antarmuka API CRUD yang lengkap dan modular untuk mengelola siklus data Purchase Order (PO).
* **Keamanan Endpoint**: Menerapkan validasi keamanan Machine-to-Machine (M2M) dengan server SSO menggunakan mekanisme API Key.
* **Integrasi SOAP Audit**: Mengembangkan SOAP Client untuk memvalidasi kecocokan Purchase Order dan mengamankan penyimpanan `soap_receipt_number` sebagai tanda bukti transaksi.
* **Event-Driven Messaging**: Memprogram fungsionalitas AMQP Publisher guna menerbitkan event `procurement.created` ke RabbitMQ ketika dokumen PO berhasil dibuat.
* **Integrasi Lintas Service**:
  - Menyediakan endpoint API integrasi khusus untuk menangani pembaruan status Purchase Order dari sistem gudang eksternal.
  - Melakukan debugging dan finalisasi Service B secara keseluruhan sebelum penggabungan kode ke branch utama.
  - Merevisi payload request otentikasi SSO M2M dengan menambahkan parameter NIM untuk memenuhi instruksi teknis terbaru dari dosen.

---

## 3. Rizwan Saputra (102022430048) - Service C (Expedition)
*Melakukan perancangan dan implementasi Service C melalui branch khusus `rizwan-service-c`.*

* **Layanan Logistik**: Membangun REST API fungsional untuk mendokumentasikan data manifest pengiriman logistik masuk (`inbound-shipments`).
* **Autentikasi Keamanan**: Mengembangkan middleware `VerifyJwtSso` untuk memvalidasi kecocokan token JWT secara dinamis menggunakan JWKS dari server SSO.
* **Integrasi SOAP Audit**: Menyusun SOAP Client untuk melakukan pendaftaran manifest ke sistem audit eksternal sekaligus menyimpan `legacy_receipt_number` sebagai bukti integrasi.
* **Event-Driven Messaging**: Membuat modul AMQP Publisher untuk mengirimkan informasi/event `shipment.created` via RabbitMQ saat ekspedisi siap diproses.
* **Finalisasi & Refactoring**:
  - Menyelesaikan proses integrasi internal Service C secara mandiri sebelum digabungkan ke repositori utama kelompok.
  - Mengoptimalkan fungsi parser token JWT dan melakukan update pada pencatatan log aktivitas integrasi service.
  - Melakukan penyesuaian fungsionalitas request M2M ke SSO sesuai instruksi terbaru dari dosen pengampu.
