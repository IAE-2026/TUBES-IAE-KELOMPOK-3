# RESUME KONTRIBUSI TIM & INDIVIDU (KELOMPOK 03)

## 1. Ahmad Rizky (102022400004) - Service A (Inventory)
- Melakukan pengembangan Service A pada branch rizky-service-a.
- Melakukan setup database MySQL lokal, migrasi tabel components, serta implementasi controller dan model inventori.
- Mengimplementasikan validasi JWT SSO pada Service A.
- Mengembangkan SOAP XML Client untuk proses audit Receive Stock dan penyimpanan ReceiptNumber.
- Mengembangkan AMQP Publisher untuk event component.received.
- Melakukan merge branch rizky-service-a ke repository utama kelompok.
- Melakukan merge branch fatir-service-b ke repository utama kelompok.
- Melakukan merge branch rizwan-service-c ke repository utama kelompok.
- Membuat konfigurasi docker-compose.yml untuk menjalankan seluruh service secara terintegrasi.
- Mengembangkan dan mengonfigurasi Nginx API Gateway sebagai pintu masuk tunggal sistem.
- Memastikan komunikasi antar-service berjalan pada lingkungan Docker terpadu.

## 2. RADEN FATIR PAUNDRAYUDHA AIRLANGGA AFFANDHI (102022430058) - Service B (Procurement)
- Mengembangkan Service B pada branch fatir-service-b.
- Membuat skema database SQLite beserta tabel procurements dan procurement_items.
- Mengembangkan API CRUD Purchase Order (PO).
- Mengimplementasikan integrasi SSO M2M menggunakan API Key.
- Mengembangkan SOAP Client untuk validasi Purchase Order dan penyimpanan soap_receipt_number.
- Mengembangkan AMQP Publisher untuk event procurement.created.
- Mengembangkan endpoint integrasi untuk penyelesaian status Purchase Order dari sistem gudang.
- Melakukan finalisasi integrasi Service B sebelum proses merge.
- Merevisi request body integrasi SSO M2M dengan penambahan parameter NIM sesuai kebutuhan terbaru.

## 3. Rizwan Saputra (102022430048) - Service C (Expedition)
- Mengembangkan Service C pada branch rizwan-service-c.
- Mengembangkan REST API untuk pencatatan manifest logistik masuk (inbound-shipments).
- Mengimplementasikan middleware VerifyJwtSso menggunakan JWKS dari server SSO.
- Mengembangkan SOAP Client untuk registrasi manifest ke sistem audit dan penyimpanan legacy_receipt_number.
- Mengembangkan AMQP Publisher untuk event shipment.created.
- Melakukan finalisasi integrasi Service C sebelum proses merge ke repository utama.
- Melakukan penyempurnaan parser token JWT dan pembaruan log integrasi service.
- Menyesuaikan implementasi request SSO M2M mengikuti instruksi terbaru dari dosen.