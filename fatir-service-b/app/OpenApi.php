<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    description: "API untuk mengelola Purchase Order (PO) pengadaan bahan baku komponen elektronik pada pabrik. Merupakan bagian dari ekosistem Inbound Supply Chain.",
    title: "Service B - Procurement API (Pengadaan Bahan Baku)",
    contact: new OA\Contact(
        name: "Tim Procurement",
        email: "procurement@factory.com"
    )
)]
#[OA\Server(
    url: "/api/v1",
    description: "Procurement Service API v1"
)]
#[OA\SecurityScheme(
    securityScheme: "X-IAE-KEY",
    type: "apiKey",
    name: "X-IAE-KEY",
    in: "header",
    description: "API Key untuk autentikasi. Gunakan NIM Mahasiswa sebagai value."
)]
class OpenApi
{
}
