<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../koneksi/koneksi.php";

if (!array_intersect(['admin', 'panitia'], $_SESSION['roles'])) {
    die("Anda tidak punya akses.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $jenis = $_POST['jenis'];
    $sumber = $_POST['sumber'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];

    if (!in_array($jenis, ['masuk', 'keluar'])) {
        $_SESSION['error'] = "Jenis harus 'masuk' atau 'keluar'.";
        header("Location: ../view/tambah_keuangan.php");
        exit();
    }

    if ($jumlah <= 0) {
        $_SESSION['error'] = "Jumlah harus lebih dari 0.";
        header("Location: ../view/tambah_keuangan.php");
        exit();
    }
    
    $sql = "INSERT INTO keuangan (tanggal, jenis, sumber, jumlah, keterangan) VALUES (?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);

    if (!$stmt) {
        $_SESSION['error'] = "Prepare statement gagal: " . $koneksi->error;
        header("Location: ../view/tambah_keuangan.php");
        exit();
    }

    $stmt->bind_param("sssds", $tanggal, $jenis, $sumber, $jumlah, $keterangan);

    if (!$stmt->execute()) {
        $_SESSION['error'] = "Eksekusi query gagal: " . $stmt->error;
        header("Location: ../view/tambah_keuangan.php");
        exit();
    } else {
        header("Location: ../view/dashboard.php");
        $_SESSION['success'] = "Data keuangan berhasil ditambahkan.";
        exit();
    }
} else {
    header("Location: ../view/tambah_keuangan.php");
    exit();
}
