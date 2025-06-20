<?php
include '../koneksi/koneksi.php';

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $jenis = $_POST['jenis'];
    $jumlah = $_POST['jumlah'];
    $total_berat = $_POST['total_berat'];
    $biaya_total = $_POST['biaya_total'];
    $query = "UPDATE hewan_qurban SET 
                jenis = '$jenis', 
                jumlah = $jumlah, 
                total_berat = $total_berat, 
                biaya_total = $biaya_total 
              WHERE id = $id";

    if ($koneksi->query($query)) {
        $cek_penerima = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pembagian_daging WHERE hewan_id = $id");
        $data_penerima = mysqli_fetch_assoc($cek_penerima);
        $total_penerima = $data_penerima['total'];

        if ($total_penerima > 0) {
            $jatah_per_orang = round($total_berat / $total_penerima, 2);
            $update_pembagian = mysqli_query($koneksi, "
                UPDATE pembagian_daging
                SET jumlah_kg = $jatah_per_orang
                WHERE hewan_id = $id
            ");
        }

        header("Location: ../view/dashboard.php");
        exit;
    } else {
        echo "Gagal update data: " . $koneksi->error;
    }
} else {
    echo "Permintaan tidak valid.";
}
?>
