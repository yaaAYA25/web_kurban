<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Tambah Data Keuangan</title>
</head>
<body>

<h2>Tambah Data Keuangan</h2>

<?php if (isset($_SESSION['success'])): ?>
    <div style="color: green; font-weight: bold;">
        <?= htmlspecialchars($_SESSION['success']); ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div style="color: red; font-weight: bold;">
        <?= htmlspecialchars($_SESSION['error']); ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form action="../proses/proses_tambah_keuangan.php" method="post">
    <label for="tanggal">Tanggal:</label><br />
    <input type="date" name="tanggal" id="tanggal" required /><br /><br />

    <label for="jenis">Jenis:</label><br />
    <select name="jenis" id="jenis" required>
        <option value="">--Pilih Jenis--</option>
        <option value="masuk">Masuk</option>
        <option value="keluar">Keluar</option>
    </select><br /><br />

    <label for="sumber">Sumber:</label><br />
    <input type="text" name="sumber" id="sumber" maxlength="100" required /><br /><br />

    <label for="jumlah">Jumlah:</label><br />
    <input type="number" name="jumlah" id="jumlah" step="0.01" min="0.01" required /><br /><br />

    <label for="keterangan">Keterangan:</label><br />
    <textarea name="keterangan" id="keterangan" rows="4"></textarea><br /><br />

    <button type="submit">Simpan</button>
</form>

</body>
</html>
