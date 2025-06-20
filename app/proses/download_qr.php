<?php
$nama = $_GET['nama'] ?? 'Anonim';
$nik = $_GET['nik'] ?? '0000000000000000';
$role = $_GET['role'] ?? 'Tamu';
$total_kg = $_GET['kg'] ?? 0;

$qrText = "Nama: $nama\nNIK: $nik\nRole: $role\nDaging (kg): $total_kg";
$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrText);
$qrImage = file_get_contents($qrUrl);
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="QR_' . preg_replace('/\s+/', '_', $nama) . '.png"');
echo $qrImage;
exit;
