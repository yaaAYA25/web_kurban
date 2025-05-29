<!DOCTYPE html>
<html>
<head>
    <title>Input Hewan Qurban</title>
</head>
<body>
    <h2>Form Input Hewan Qurban</h2>

    <form action="../proses/proses_hewan.php" method="POST">
        <label>Jenis Hewan:</label>
        <select name="jenis" required>
            <option value="kambing">Kambing</option>
            <option value="sapi">Sapi</option>
        </select><br><br>

        <label>Jumlah:</label>
        <input type="number" name="jumlah" required><br><br>

        <label>Total Berat (kg):</label>
        <input type="number" name="total_berat" required><br><br>

        <label>Biaya Total (Rp):</label>
        <input type="number" step="0.01" name="biaya_total" required><br><br>

        <button type="submit">Simpan</button>
    </form>
</body>
</html>
