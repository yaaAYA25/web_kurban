<?php
session_start();
require_once "../koneksi/koneksi.php";

if (!in_array('admin', $_SESSION['roles'])) {
    die("Akses ditolak.");
}

$rolesData = $_POST['roles'];
$userIDs = $_POST['user_id'];

// Hitung rencana total panitia & kurban
$totalPanitia = 0;
$totalKurban = 0;

foreach ($rolesData as $warga_id => $roles) {
    foreach ($roles as $r) {
        if ($r == 'panitia') $totalPanitia++;
        if ($r == 'kurban') $totalKurban++;
    }
}

// Validasi batas
if ($totalPanitia > 15 || $totalKurban > 9) {
    die("❌ Gagal menyimpan. Jumlah Panitia maksimal 15 & Kurban maksimal 9. <a href='kelola_warga.php'>Kembali</a>");
}

// Hapus semua role user dulu
foreach ($userIDs as $warga_id => $uid) {
    $koneksi->query("DELETE FROM user_roles WHERE user_id = $uid");
}

// Tambahkan ulang role sesuai input
foreach ($rolesData as $warga_id => $roles) {
    $uid = $userIDs[$warga_id];
    foreach ($roles as $r) {
        $koneksi->query("INSERT INTO user_roles (user_id, role) VALUES ($uid, '$r')");
    }
}

echo "✅ Role berhasil diperbarui. <a href='../view/dashboard.php'>Kembali</a>";
