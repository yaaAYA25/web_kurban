<?php
// Ambil ID warga dari session atau GET
session_start();
$warga_id = isset($_GET['warga_id']) ? intval($_GET['warga_id']) : 0;

if ($warga_id <= 0) {
    if (!isset($_SESSION['username'])) {
        die("Akses ditolak.");
    }
    $username = $_SESSION['username'];
    $queryUser = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($queryUser);
    $warga_id = $user['warga_id'];
}

// Ambil data warga
$sqlWarga = "SELECT nama, nik FROM warga WHERE id_warga = ?";
$stmtWarga = mysqli_prepare($koneksi, $sqlWarga);
mysqli_stmt_bind_param($stmtWarga, "i", $warga_id);
mysqli_stmt_execute($stmtWarga);
$resultWarga = mysqli_stmt_get_result($stmtWarga);
$dataWarga = mysqli_fetch_assoc($resultWarga);

if (!$dataWarga) {
    die("Data warga tidak ditemukan.");
}

// Hitung total daging untuk role ini (sapi + kambing)
$sql = "
    SELECT SUM(pd.jumlah_kg) AS total_kg
    FROM pembagian_daging pd
    WHERE pd.warga_id = ? AND pd.kategori = ?
";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "is", $warga_id, $role);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$total_kg = $row['total_kg'] ?? 0;

// Buat QR
$qrText = "Nama: {$dataWarga['nama']}\nNIK: {$dataWarga['nik']}\nRole: {$role}\nDaging (kg): {$total_kg}";
$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrText);
?>

<h2>QR Code untuk <?= htmlspecialchars($dataWarga['nama']) ?> (<?= htmlspecialchars($role) ?>)</h2>
<p><strong>NIK:</strong> <?= htmlspecialchars($dataWarga['nik']) ?></p>
<p><strong>Daging (kg):</strong> <?= htmlspecialchars($total_kg) ?></p>
<img src="<?= $qrUrl ?>" alt="QR Code <?= htmlspecialchars($role) ?>"><br>
<a href="../proses/download_qr.php?nama=<?=urlencode($dataWarga['nama']) ?>&nik=<?= urlencode($dataWarga['nik']) ?>&role=<?= urlencode($role) ?>&kg=<?= urlencode($total_kg) ?>">
    <button>Download QR Code</button>
</a>
