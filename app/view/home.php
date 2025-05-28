<?php 
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../index.php");
    exit();
}

$roles = $_SESSION['roles']; // Ini harus array, misal: ['panitia', 'warga']
$username = $_SESSION['username'];

// Fungsi bantu untuk cek apakah user punya role tertentu
// Admin otomatis dianggap punya semua role
function hasRole($roleName, $roles) {
    return in_array('admin', $roles) || in_array($roleName, $roles);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <h2>Selamat datang, <?= htmlspecialchars($username) ?></h2>
    

  
    <?php if (hasRole('panitia', $roles)): ?>
        <h3>Dashboard Panitia</h3>
        <p>Ini halaman utama untuk Panitia.</p>
        <a href="./tambah_keuangan.php">Lanjut ke Dashboard Panitia</a>
        <a href="./info_keuangan.php">Info Keuangan</a>
        <a href="./pembagian_daging.php">Pembagian Daging</a>
        <a href="lihat_qr">Lihat QR Panitia</a>
    <?php endif; ?>

    <?php if (hasRole('kurban', $roles)): ?>
        <h3>Dashboard Peserta Kurban</h3>
        <p>Ini halaman utama untuk Peserta Kurban.</p>
        <a href="./info_keuangan.php">Info Keuangan</a>
        <a href="lihat_qr_kurban">Lihat QR Pengkurban</a>
    <?php endif; ?>

    <?php if (hasRole('warga', $roles)): ?>
        <h3>Dashboard Warga</h3>
        <p>Ini halaman utama untuk Warga.</p>
        <a href="./info_roles.php">Info Peserta dan Panitia</a>
        <a href="./info_hewan.php">Info Hewan Qurban</a>
        <a href="./info_pembagian.php">Info Pembagian Daging</a>
        <a href="lihat_qr">Lihat QR Warga</a>
    <?php endif; ?>
</body>
</html>
  <?php if (hasRole('admin', $roles)): ?>
        <h3>Dashboard Admin</h3>
        <p>Ini halaman utama untuk Admin.</p>
        <a href="kelola-warga.php">Kelola Warga</a>
        <a href="./info_roles.php">Info Peserta dan Panitia</a>
        <a href="./hewan_qurban.php">Input Data Hewan</a>
        <a href="./pembagian_daging.php">Pembagian Daging</a>
        <a href="./info_pembagian.php">Info Pembagian Daging</a>
    <?php endif; ?>
