# ANALISIS TUGAS 3 INTEGRASI APLIKASI ENTERPRISE

**Nama:** Rizwan Saputra  
**NIM:** 102022430048  
**Kelas:** SI-48-09  
**Kelompok:** 03  
**Service:** Service C - Pengiriman/Expedition  

---

### Justifikasi Pemilihan Transaksi Kritis
Saya memilih transaksi **Inbound Shipment (Penerimaan Manifest Pengiriman)** sebagai transaksi kritis yang diintegrasikan ke infrastruktur pusat dengan alasan berikut:

1. **Mengubah Status Ketersediaan Stok & Perencanaan Lini Produksi:** 
   Penerimaan manifest/resi pengiriman merupakan titik awal pergerakan fisik barang dalam rantai pasok (*supply chain*). Transaksi ini mengubah status kargo menjadi `on_the_way` dan menetapkan estimasi waktu tiba (*estimated_arrival*), sehingga pabrik (terutama lini produksi pada Service A) dapat bersiap menyambut bahan baku.

2. **Kewajiban Audit Lintas Sektoral (Legacy SOAP):** 
   Karena logistik berkaitan langsung dengan nilai aset dan operasional fisik perusahaan, setiap pergerakan barang masuk harus diaudit secara ketat. Mengirimkan catatan manifest ke sistem **Legacy SOAP Audit** memastikan data resi disimpan secara permanen, terpusat, dan tidak dapat dimanipulasi demi kepatuhan regulasi internal perusahaan.

3. **Memicu Reaksi Cepat Layanan Lain (RabbitMQ Broker):** 
   Setelah manifest diterima dan resi diterbitkan, data harus segera disebarkan secara asinkron menggunakan RabbitMQ. Dengan begitu, departemen gudang (Service A) dan pemesanan (Service B) dapat langsung memperbarui status mereka tanpa harus menunggu *polling request* manual, mewujudkan integrasi waktu nyata (*real-time integration*).

---

### Sequence Diagram Interaksi Layanan Terpusat (SSO, SOAP, RabbitMQ)
Berikut adalah alur interaksi terintegrasi *Service C* dengan sistem keamanan (SSO), audit (SOAP), dan distribusi data (RabbitMQ):

![Sequence Diagram Inbound Shipment](Output%20Tugas%203/Inbound%20Shipment.jpg)
