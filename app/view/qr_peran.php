<?php
session_start();
require_once "../koneksi/koneksi.php";

$role = isset($role) ? $role : 'warga';
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

$sqlWarga = "SELECT nama, nik FROM warga WHERE id_warga = ?";
$stmtWarga = mysqli_prepare($koneksi, $sqlWarga);
mysqli_stmt_bind_param($stmtWarga, "i", $warga_id);
mysqli_stmt_execute($stmtWarga);
$resultWarga = mysqli_stmt_get_result($stmtWarga);
$dataWarga = mysqli_fetch_assoc($resultWarga);

if (!$dataWarga) {
    die("Data warga tidak ditemukan.");
}

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

$qrText = "Nama: {$dataWarga['nama']}\nNIK: {$dataWarga['nik']}\nRole: {$role}\nDaging (kg): {$total_kg}";
$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrText);
?>

<style>
    .container {
        display: flex;
        gap: 32px;
        max-width: 900px;
        margin: 10px auto;
        padding: 0 10px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
    }
    .qr-card {
        flex: 0 0 350px; 
        background: #ffffff;
        padding: 28px 32px;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(26,156,184,0.15);
        color: #222;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .qr-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 30px rgba(26,156,184,0.3);
    }
    .qr-card h2 {
        font-size: 22px;
        color: #0c7b9b;
        margin-bottom: 18px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-align: center;
    }
    .qr-card p {
        font-size: 16px;
        margin: 8px 0;
        color: #444;
        width: 100%;
        text-align: center;
    }
    .qr-card img {
        margin-top: 24px;
        width: 220px;
        height: 220px;
        border-radius: 14px;
        box-shadow: 0 6px 16px rgba(26,156,184,0.3);
        object-fit: cover;
    }
    .qr-download-btn {
        margin-top: 26px;
        padding: 12px 26px;
        background-color: #0c7b9b;
        color: #fff;
        font-size: 15px;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        box-shadow: 0 5px 14px rgba(12,123,155,0.45);
        transition: background 0.25s ease-in-out, box-shadow 0.25s ease-in-out;
        width: 100%;
        text-align: center;
        display: inline-block;
    }
    .qr-download-btn:hover {
        background-color: #095f71;
        box-shadow: 0 7px 22px rgba(9,95,113,0.65);
    }
    .qr-info-small {
        display: block;
        margin-top: 14px;
        font-size: 13px;
        color: #6c757d;
        text-align: center;
        font-style: italic;
        user-select: none;
    }
    .right-column {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 28px;
        max-width: 520px;
    }
    .info-card {
        background: #e6f4fa;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(26,156,184,0.12);
        padding: 28px 32px;
        font-size: 16px;
        color: #0c7b9b;
        line-height: 1.7;
        font-weight: 600;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    .info-card h3 {
        margin: 0 0 14px 0;
        font-weight: 800;
        font-size: 20px;
        letter-spacing: 0.03em;
    }
    .info-card ul {
        list-style-type: disc;
        padding-left: 22px;
        margin: 0;
        color: #0a6681;
    }
    .info-card ul li {
        margin-bottom: 10px;
    }
    .bar-chart {
        width: 100%;
    }
    .bar-label {
        font-size: 14px;
        font-weight: 700;
        color: #0a6681;
        margin-bottom: 8px;
    }
    .bar {
        height: 20px;
        border-radius: 10px;
        margin-bottom: 16px;
        background-color: #c0dced;
        position: relative;
        overflow: hidden;
    }
    .bar-inner {
        height: 100%;
        background-color: #0c7b9b;
        border-radius: 10px;
        text-align: right;
        padding-right: 14px;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        line-height: 20px;
        box-shadow: inset 0 -2px 8px rgba(0,0,0,0.15);
        transition: width 0.4s ease;
    }
    .quote {
        font-style: italic;
        font-size: 15px;
        color: #356e87;
        border-left: 5px solid #0c7b9b;
        padding-left: 18px;
        margin-top: 20px;
        font-weight: 600;
        letter-spacing: 0.03em;
    }

    /* Testimoni card */
    .testimonial-card {
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(12,123,155,0.1);
        padding: 24px 28px;
        font-size: 15px;
        color: #1a3d57;
        line-height: 1.6;
        font-weight: 600;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .testimonial-card p {
        margin: 0;
        font-style: italic;
        color: #2e5c7d;
    }
    .testimonial-author {
        font-weight: 700;
        font-size: 14px;
        text-align: right;
        color: #0c7b9b;
    }
    .qr-footer {
        margin: 50px auto 20px auto;
        font-size: 14px;
        color: #666;
        text-align: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        user-select: none;
        max-width: 900px;
    }
    .qr-footer a {
        color: #0c7b9b;
        text-decoration: none;
        font-weight: 700;
        transition: color 0.2s ease-in-out;
    }
    .qr-footer a:hover {
        text-decoration: underline;
        color: #095f71;
    }
    @media (max-width: 880px) {
        .container {
            flex-direction: column;
            max-width: 100%;
            padding: 0 15px;
            gap: 24px;
        }
        .qr-card {
            flex: none;
            max-width: 100%;
            width: 320px;
            margin: 0 auto;
        }
        .right-column {
            max-width: 100%;
        }
        .qr-card img {
            width: 180px;
            height: 180px;
        }
    }
</style>

<div class="container">
    <div class="qr-card">
        <h2>QR Code untuk <?= htmlspecialchars($dataWarga['nama']) ?> (<?= htmlspecialchars($role) ?>)</h2>
        <p><strong>NIK:</strong> <?= htmlspecialchars($dataWarga['nik']) ?></p>
        <p><strong>Daging (kg):</strong> <?= htmlspecialchars($total_kg) ?></p>
        <img src="<?= $qrUrl ?>" alt="QR Code <?= htmlspecialchars($role) ?>">
        <a href="../proses/download_qr.php?nama=<?= urlencode($dataWarga['nama']) ?>&nik=<?= urlencode($dataWarga['nik']) ?>&role=<?= urlencode($role) ?>&kg=<?= urlencode($total_kg) ?>" class="qr-download-btn" aria-label="Download QR Code">
            Download QR Code
        </a>
        <small class="qr-info-small">QR Code ini hanya untuk keperluan pengambilan daging qurban.</small>
    </div>

    <div class="right-column">
        <div class="info-card" role="region" aria-labelledby="info-petunjuk">
            <h3 id="info-petunjuk">Petunjuk Penggunaan</h3>
            <ul>
                <li>Scan QR untuk verifikasi data.</li>
                <li>Tunjukkan QR saat pengambilan daging.</li>
                <li>QR unik sesuai role dan data warga.</li>
                <li>Jaga kerahasiaan QR Code Anda.</li>
            </ul>

            <div class="bar-chart" aria-label="Jumlah daging dalam kilogram">
                <div class="bar-label">Jumlah Daging (kg)</div>
                <div class="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?= min(100, $total_kg * 10) ?>">
                    <div class="bar-inner" style="width: <?= min(100, $total_kg * 10) ?>%;"><?= $total_kg ?> kg</div>
                </div>
            </div>

            <blockquote class="quote" aria-label="Testimoni pengguna">
                “Sistem QR Code memudahkan pengambilan daging tanpa antre panjang. Sangat praktis dan aman!” <br><br>– Sulton, Ketua RT 001
            </blockquote>
        </div>
    </div>
</div>

<div class="qr-footer">
    &copy; 2025 Sistem Qurban RT 001. Developed by <a href="#" target="_blank" rel="noopener noreferrer">Your Name</a>.
</div>
