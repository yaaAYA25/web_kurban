<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="../proses/proses_pembagian.php" method="POST">
        <label>Pilih Jenis Hewan:</label><br>
        <select name="jenis" required>
            <option value="">-- Pilih Jenis --</option>
            <option value="sapi">Sapi</option>
            <option value="kambing">Kambing</option>
        </select>
        <br><br>
        <button type="submit">Bagikan Sekarang</button>
    </form>
</body>

</html>