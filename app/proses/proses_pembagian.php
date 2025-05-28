<?php
include '../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jenis'])) {
    $jenis = $_POST['jenis'];
    $tanggal = date('Y-m-d');

    // Ambil semua hewan jenis itu
    $hewan_query = mysqli_query($koneksi, "SELECT * FROM hewan_qurban WHERE jenis = '$jenis'");
    $total_berat = 0;
    $list_hewan_id = [];

    while ($row = mysqli_fetch_assoc($hewan_query)) {
        // Cek apakah hewan ini sudah dibagikan
        $hewan_id = $row['id'];
        $cek = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pembagian_daging WHERE hewan_id = $hewan_id");
        $data_cek = mysqli_fetch_assoc($cek);
        if ($data_cek['total'] > 0) {
            continue; // skip hewan yang sudah dibagikan
        }

        $total_berat += $row['total_berat'];
        $list_hewan_id[] = $hewan_id;
    }

    if (count($list_hewan_id) === 0) {
        echo "<script>alert('Semua hewan $jenis sudah dibagikan!'); window.location.href='../view/pembagian_daging.php';</script>";
        exit;
    }

    // Ambil semua user dengan role yang berhak
    $roles = mysqli_query($koneksi, "
        SELECT ur.user_id, ur.role, w.id_warga
        FROM user_roles ur
        JOIN users u ON ur.user_id = u.id_user
        JOIN warga w ON u.warga_id = w.id_warga
        WHERE ur.role IN ('warga', 'panitia', 'kurban')
    ");

    $list_penerima = [];
    $total_peran = 0;

    while ($row = mysqli_fetch_assoc($roles)) {
        $id_warga = $row['id_warga'];
        $role = $row['role'];

        $total_peran++;
        $list_penerima[] = [
            'warga_id' => $id_warga,
            'role' => $role,
        ];
    }

    if ($total_peran == 0 || $total_berat == 0) {
        echo "Data tidak lengkap atau total berat kosong.";
        exit;
    }

    // Hitung jatah per peran
    $jatah_peran = $total_berat / $total_peran;

    // Simpan ke pembagian_daging untuk setiap penerima dan setiap hewan_id
    foreach ($list_penerima as $penerima) {
        foreach ($list_hewan_id as $hewan_id) {
            $warga_id = $penerima['warga_id'];
            $kategori = $penerima['role'];
            $jumlah_kg = number_format($jatah_peran, 2);

            mysqli_query($koneksi, "
                INSERT INTO pembagian_daging (warga_id, hewan_id, kategori, jumlah_kg, tanggal)
                VALUES ($warga_id, $hewan_id, '$kategori', $jumlah_kg, '$tanggal')
            ");
        }
    }

    echo "<script>alert('Pembagian daging jenis $jenis berhasil!'); window.location.href='../view/pembagian_daging.php';</script>";
} else {
    echo "Akses tidak valid!";
}
?>
