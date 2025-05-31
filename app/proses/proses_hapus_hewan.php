<?php
include '../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus pembagian daging yang terkait hewan ini
    $hapus_pembagian = mysqli_query($koneksi, "DELETE FROM pembagian_daging WHERE hewan_id = $id");

    // Hapus data hewan
    $hapus_hewan = mysqli_query($koneksi, "DELETE FROM hewan_qurban WHERE id = $id");

    if ($hapus_hewan) {
        echo "<script>alert('Data hewan dan pembagian terkait berhasil dihapus!'); window.location.href='../view/info_hewan_edit.php';</script>";
    } else {
        echo "Gagal menghapus data: " . $koneksi->error;
    }
} else {
    echo "ID tidak ditemukan.";
}
?>
