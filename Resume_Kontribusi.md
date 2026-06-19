NAMA: RIZWAN SAPUTRA
NIM: 102022430048
KELAS: SI-48-09

Resume Kontribusi Pengerjaan IAE (Tugas 2, 3, dan Tugas Besar)

1. Pembuatan Service (Tugas 2 Individu)
Saya membuat service dengan tema Supply Chain, mengurus bagian Pengiriman/Expedition (Service C).
- Membangun proyek dari awal menggunakan Laravel.
- Membuat Logic Controller dan memasang Middleware untuk menangani keamanan dan proses bisnis pengiriman.
- Mengintegrasikan Swagger untuk membuat dokumentasi API
- Menggunakan GraphQL sebagai query data pengiriman

2. Konfigurasi Docker
Membuat service yang saya buat berjalan di docker biar mudah di deploy.
- Mengonfigurasi file docker-compose.ym dan Dockerfile.
- Menggunakan SQLite sebagai database di dalam .env Docker.

3. Integrasi SSO M2M & Audit SOAP Legacy (Tugas 3 Individu)
Menghubungkan Service C ke Enterprise Digital City punya Pak Ekky.
- Membuat class khusus IaeCentralService.php yang bertugas sebagai komunikasi HTTP ke server pusat.
- Melakukan request ke endpoint SSO M2M untuk mendapatkan token akses (Token JWT).
- Konfigurasi SOAP Client untuk berkomunikasi dengan layanan legacy.
- Menambahkan kolom legacy_receipt_number ke dalam tabel inbound_shipments agar nomor resi dari server pak ekky dapat disimpan di database lokal.

4. Implementasi RabbitMQ

Melakukan integrasi pada aplikasi dengan RabbitMQ dengan membuat publisher.
Membuat setiap ada pengiriman baru yang dibuat, service akan publish event shipment.a.created 

5. Rincian Pengerjaan Tugas Besar (Tubes)
Pada Tugas Besar, saya menyesuaikan semuanya agar sesuai kontrak, proses bisnis, dan siap dihubungkan dengan service milik teman kelompok saya.
- Menyesuaikan alur Service C.
- Memperbaiki parser JWT dan memastikan middleware VerifyJwtSso dapat memvalidasi token yang masuk dari service lain dengan benar.
- Memastikan SOAP Client dan RabbitMQ bisa jalan tanpa masalah. Melakukan beberapa commit  untuk memastikan kode sudah bi dipakai secara penuh dalam skenario Tugas Besar lintas kelompok. 


