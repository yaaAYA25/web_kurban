<?php
session_start();
require_once "../koneksi/koneksi.php";

if (!in_array('admin', $_SESSION['roles'])) {
    die("Akses ditolak.");
}

$rolesData = $_POST['roles'];
$userIDs = $_POST['user_id'];
$kategoriHewan = $_POST['kategori_hewan'] ?? []; // Data kategori hewan kurban

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

// Hapus semua role user dulu dan relasi hewan juga
foreach ($userIDs as $warga_id => $uid) {
    // Cari semua user_role_id milik user ini
    $resRoles = $koneksi->query("SELECT id FROM user_roles WHERE user_id = $uid");
    while ($roleRow = $resRoles->fetch_assoc()) {
        $user_role_id = $roleRow['id'];
        // Hapus relasi hewan kurban berdasarkan user_role_id
        $koneksi->query("DELETE FROM relasi_user_hewan WHERE user_role_id = $user_role_id");
    }
    // Hapus role user
    $koneksi->query("DELETE FROM user_roles WHERE user_id = $uid");
}

// Tambahkan ulang role sesuai input dan simpan relasi hewan kalau role kurban
foreach ($rolesData as $warga_id => $roles) {
    $uid = $userIDs[$warga_id];
    foreach ($roles as $r) {
        // Insert ke user_roles
        $koneksi->query("INSERT INTO user_roles (user_id, role) VALUES ($uid, '$r')");
        $user_role_id = $koneksi->insert_id; // dapatkan id insert baru

        if ($r == 'kurban') {
            $hewan = $kategoriHewan[$warga_id] ?? '';

            if ($hewan === 'sapi' || $hewan === 'kambing') {
                // Cari id hewan_qurban sesuai jenis hewan
                $result = $koneksi->query("SELECT id FROM hewan_qurban WHERE jenis = '$hewan' LIMIT 1");
                if ($result && $result->num_rows > 0) {
                    $hewan_id = $result->fetch_assoc()['id'];

                    // Insert relasi user dan hewan_qurban
                    $koneksi->query("INSERT INTO relasi_user_hewan (user_role_id, hewan_qurban_id) VALUES ($user_role_id, $hewan_id)");
                }
            }
        }
    }
}

echo "✅ Role berhasil diperbarui. <a href='../view/dashboard.php'>Kembali</a>";
?>