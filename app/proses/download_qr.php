<?php
// Pastikan sudah dapat data yang mau dimasukkan ke QR
$nama = $_GET['nama'] ?? 'Anonim';
$nik = $_GET['nik'] ?? '0000000000000000';
$role = $_GET['role'] ?? 'Tamu';
$total_kg = $_GET['kg'] ?? 0;

$qrText = "Nama: $nama\nNIK: $nik\nRole: $role\nDaging (kg): $total_kg";

// Generate QR dari API eksternal
$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrText);

// Ambil isi QR sebagai file
$qrImage = file_get_contents($qrUrl);

// Kirim header agar langsung terunduh
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="QR_' . preg_replace('/\s+/', '_', $nama) . '.png"');
echo $qrImage;
exit;
