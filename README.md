# Expedition Service (Service C - Pengiriman)

## Tugas Integrasi Aplikasi Enterprise (IAE)

### Identitas Mahasiswa

- **Nama:** Rizwan Saputra
- **NIM:** 102022430048
- **Kelas:** SI-48-09
- **Mata Kuliah:** BBK2HAB3 - Integrasi Aplikasi Enterprise

### Deskripsi Service

Service ini bertugas untuk mengelola data pengiriman (Inbound Shipments) menggunakan protokol komunikasi modern (REST API & GraphQL). Service ini dibangun mematuhi Standard Integration Contract sehingga siap untuk diintegrasikan dengan service milik anggota kelompok lainnya dalam ekosistem "The Enterprise Digital City".

### Fungsionalitas Utama

Service ini memiliki fungsionalitas berikut:

- **REST API (Minimum Viable API):**
  - `GET /api/v1/inbound-shipments` : Mengambil daftar seluruh data pengiriman.
  - `GET /api/v1/inbound-shipments/{id}` : Mengambil data spesifik suatu pengiriman berdasarkan ID.
  - `POST /api/v1/inbound-shipments` : Menambah data pengiriman baru.

- **GraphQL:**
  Tersedia endpoint query via GraphQL dengan kapabilitas seleksi field yang dinamis untuk data pengiriman.

- **Security:**
  Seluruh endpoint dilindungi oleh sistem Autentikasi terpusat (SSO M2M dengan JWT Token) yang terintegrasi dengan middleware `VerifyJwtSso`.

- **Interactive Documentation:**
  Dokumentasi lengkap REST API dapat diakses melalui antarmuka interaktif Swagger UI.

- **Message Broker (RabbitMQ):**
  Service mempublish event `shipment.a.created` secara asinkron setiap kali ada pengiriman baru yang dibuat.

- **Legacy Integration (SOAP):**
  Terintegrasi dengan layanan legacy melalui protokol SOAP untuk mencatat dan mendapatkan `legacy_receipt_number`.
