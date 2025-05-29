<?php
require_once "../koneksi/koneksi.php";

// Ambil role dari GET (default ke 'warga' kalau tidak ada)
$role = isset($_GET['role']) ? $_GET['role'] : 'warga';

// Cek ID warga
$warga_id = isset($_GET['warga_id']) ? intval($_GET['warga_id']) : 0;
if ($warga_id <= 0) {
    session_start();
    if (!isset($_SESSION['username'])) {
        die("Akses ditolak.");
    }
    $username = $_SESSION['username'];
    $queryUser = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($queryUser);
    $warga_id = $user['warga_id'];
}

// Ambil data utama warga
$sqlWarga = "SELECT w.nama, w.nik FROM warga w WHERE w.id_warga = ?";
$stmtWarga = mysqli_prepare($koneksi, $sqlWarga);
mysqli_stmt_bind_param($stmtWarga, "i", $warga_id);
mysqli_stmt_execute($stmtWarga);
$resultWarga = mysqli_stmt_get_result($stmtWarga);
$dataWarga = mysqli_fetch_assoc($resultWarga);

if (!$dataWarga) {
    die("Data warga tidak ditemukan.");
}

// Ambil total daging berdasarkan role
$sqlPembagian = "SELECT IFNULL(SUM(pd.jumlah_kg), 0) AS total_kg
                 FROM pembagian_daging pd
                 JOIN user_roles ur ON ur.user_id = pd.warga_id
                 WHERE pd.warga_id = ? AND ur.role = ?";
$stmtPembagian = mysqli_prepare($koneksi, $sqlPembagian);
mysqli_stmt_bind_param($stmtPembagian, "is", $warga_id, $role);
mysqli_stmt_execute($stmtPembagian);
$resultPembagian = mysqli_stmt_get_result($stmtPembagian);
$dataPembagian = mysqli_fetch_assoc($resultPembagian);

$total_kg = $dataPembagian['total_kg'] ?? 0;

// Generate isi QR
$qrText = "Nama: " . $dataWarga['nama'] . "\n" .
          "NIK: " . $dataWarga['nik'] . "\n" .
          "Role: " . $role . "\n" .
          "Daging (kg): " . $total_kg;

$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrText);
?>

<!-- Tampilkan data -->
<h2>QR Code untuk <?= htmlspecialchars($dataWarga['nama']) ?> (Role: <?= htmlspecialchars($role) ?>)</h2>
<p><strong>NIK:</strong> <?= htmlspecialchars($dataWarga['nik']) ?></p>
<p><strong>Daging (kg) untuk role '<?= htmlspecialchars($role) ?>':</strong> <?= htmlspecialchars($total_kg) ?></p>

<h3>QR Code:</h3>
<img src="<?= $qrUrl ?>" alt="QR Code <?= htmlspecialchars($role) ?>" />
