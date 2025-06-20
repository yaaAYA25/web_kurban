<?php
require_once "../../vendor/autoload.php"; 
use Zxing\QrReader;

if ($_FILES['qr_file']['error'] === UPLOAD_ERR_OK) {
    $tmpPath = $_FILES['qr_file']['tmp_name'];

    $qrcode = new QrReader($tmpPath);
    $text = $qrcode->text(); 

    if ($text) {
        echo "<h4>Hasil QR:</h4><pre>" . htmlspecialchars($text) . "</pre>";
    } else {
        echo "QR tidak terbaca. Pastikan kualitas gambar baik.";
    }
} else {
    echo "Upload gagal: " . $_FILES['qr_file']['error'];
}
