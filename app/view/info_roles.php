<?php
session_start();
include "./../koneksi/koneksi.php";

// Query panitia
$sql_panitia = "
    SELECT w.nama
    FROM user_roles ur
    JOIN users u ON ur.user_id = u.id_user
    JOIN warga w ON u.warga_id = w.id_warga
    WHERE ur.role = 'panitia'
    ORDER BY w.nama ASC
";
$result_panitia = $koneksi->query($sql_panitia);

// Query kurban
$sql_kurban = "
    SELECT w.nama
    FROM user_roles ur
    JOIN users u ON ur.user_id = u.id_user
    JOIN warga w ON u.warga_id = w.id_warga
    WHERE ur.role = 'kurban'
    ORDER BY w.nama ASC
";
$result_kurban = $koneksi->query($sql_kurban);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Panitia dan Kurban</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        table { border-collapse: collapse; width: 45%; background: white; margin-bottom: 30px; float: left; margin-right: 5%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        h2 { clear: both; }
    </style>
</head>
<body>

<h2>Daftar Panitia</h2>
<?php if ($result_panitia->num_rows === 0): ?>
    <p>Tidak ada panitia terdaftar.</p>
<?php else: ?>
<table>
    <thead>
        <tr><th>Nama Panitia</th></tr>
    </thead>
    <tbody>
        <?php while ($row = $result_panitia->fetch_assoc()): ?>
        <tr><td><?= htmlspecialchars($row['nama']) ?></td></tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php endif; ?>

<h2>Daftar Kurban</h2>
<?php if ($result_kurban->num_rows === 0): ?>
    <p>Tidak ada warga yang kurban terdaftar.</p>
<?php else: ?>
<table>
    <thead>
        <tr><th>Nama Kurban</th></tr>
    </thead>
    <tbody>
        <?php while ($row = $result_kurban->fetch_assoc()): ?>
        <tr><td><?= htmlspecialchars($row['nama']) ?></td></tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php endif; ?>

</body>
</html>
