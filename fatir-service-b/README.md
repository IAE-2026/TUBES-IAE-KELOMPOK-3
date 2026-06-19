# Service B: Procurement (Pengadaan Bahan Baku)
## Inbound Supply Chain - Electronics Factory

Service ini bertanggung jawab atas proses pengadaan komponen elektronik dari supplier eksternal. Merupakan bagian kedua dari alur **Inbound Supply Chain** setelah pengecekan stok (Service A).

### 📋 Fitur Utama
- **Pembuatan Purchase Order (PO)**: Menerbitkan dokumen pemesanan ke supplier.
- **Riwayat Pengadaan**: Memantau seluruh daftar pesanan yang pernah dibuat.
- **Detail Pesanan**: Melihat rincian komponen (Part Number, Qty, Price) dalam satu PO.

### 🚀 Integrasi & Keamanan
- **Base URL**: `http://localhost:8000/api/v1`
- **Security**: Menggunakan Middleware API Key.
- **Header**: `X-IAE-KEY: [NIM_MAHASISWA]`

### 🛣️ Endpoint API
| Method | Endpoint | Deskripsi |
| :--- | :--- | :--- |
| `POST` | `/procurements` | Membuat Purchase Order baru |
| `GET` | `/procurements` | Mendapatkan daftar seluruh riwayat PO |
| `GET` | `/procurements/{id}` | Mendapatkan detail satu PO spesifik |

### 🛠️ Cara Menjalankan
1. **Setup Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
2. **Database Migration & Seed**:
   ```bash
   php artisan migrate --seed
   ```
3. **Generate API Documentation**:
   ```bash
   php artisan l5-swagger:generate
   ```
4. **Run Server**:
   ```bash
   php artisan serve
   ```
   Akses API Doc di: `http://localhost:8001/api/documentation`

---
*Proyek ini dikembangkan sebagai bagian dari tugas Inter-App Engagement (IAE).*
