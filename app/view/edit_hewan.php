
include '../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM hewan_qurban WHERE id = $id";
    $result = $koneksi->query($query);

    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();
    } else {
        echo "Data tidak ditemukan.";
        exit;
    }
} else {
    echo "ID tidak ditemukan.";
    exit;
}


<!-- <!DOCTYPE html>
<html>
<head>
    <title>Edit Hewan Qurban</title>
</head>
<body>
    <h2>Edit Data Hewan Qurban</h2>
    <form method="POST" action="../proses/proses_edit_hewan.php">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <label>Jenis:</label><br>
        <select name="jenis" required>
            <option value="kambing" <?= ($data['jenis'] == 'kambing') ? 'selected' : '' ?>>Kambing</option>
            <option value="sapi" <?= ($data['jenis'] == 'sapi') ? 'selected' : '' ?>>Sapi</option>
        </select><br><br>

        <label>Jumlah:</label><br>
        <input type="number" name="jumlah" value="<?= $data['jumlah'] ?>" required><br><br>

        <label>Total Berat (kg):</label><br>
        <input type="number" name="total_berat" value="<?= $data['total_berat'] ?>" required><br><br>

        <label>Biaya Total (Rp):</label><br>
        <input type="number" name="biaya_total" value="<?= $data['biaya_total'] ?>" required><br><br>

        <button type="submit" name="update">Update</button>
    </form>
</body>
</html> -->
