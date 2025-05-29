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

<h2>Daftar Panitia</h2>
<?php if ($result_panitia->num_rows === 0): ?>
    <p>Tidak ada panitia terdaftar.</p>
<?php else: ?>
    <table style="border-collapse: collapse; width: 100%; background: white; margin-bottom: 30px;">
        <thead>
            <tr>
                <th style="border: 1px solid #ccc; padding: 8px; text-align: left; background-color: #eee;">Nama Panitia</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_panitia->fetch_assoc()): ?>
                <tr>
                    <td style="border: 1px solid #ccc; padding: 8px;"><?= htmlspecialchars($row['nama']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>

<h2>Daftar Kurban</h2>
<?php if ($result_kurban->num_rows === 0): ?>
    <p>Tidak ada warga yang kurban terdaftar.</p>
<?php else: ?>
    <table style="border-collapse: collapse; width: 100%; background: white; margin-bottom: 30px;">
        <thead>
            <tr>
                <th style="border: 1px solid #ccc; padding: 8px; text-align: left; background-color: #eee;">Nama Kurban</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_kurban->fetch_assoc()): ?>
                <tr>
                    <td style="border: 1px solid #ccc; padding: 8px;"><?= htmlspecialchars($row['nama']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>