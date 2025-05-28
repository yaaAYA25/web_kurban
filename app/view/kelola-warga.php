<?php
session_start();
include "./../koneksi/koneksi.php";

// Cek role admin
if (!in_array('admin', $_SESSION['roles'])) {
    die("Akses ditolak. Halaman ini hanya untuk Admin.");
}

// Ambil semua warga
$sql = "SELECT * FROM warga";
$query = $koneksi->query($sql);

// Hitung jumlah panitia dan kurban saat ini
$panitiaCount = $koneksi->query("SELECT COUNT(*) as total FROM user_roles WHERE role='panitia'")->fetch_assoc()['total'];
$kurbanCount = $koneksi->query("SELECT COUNT(*) as total FROM user_roles WHERE role='kurban'")->fetch_assoc()['total'];
?>

<h2>Kelola Warga dan Role</h2>

<form action="../proses/simpan_role.php" method="POST">
    <table border="1" cellpadding="5">
        <tr>
            <th>Nama</th>
            <th>Username</th>
            <th>Admin</th>
            <th>Panitia</th>
            <th>Kurban</th>
            <th>Warga</th>
        </tr>
        <?php while ($row = $query->fetch_assoc()) {
            $user_id = $row['id_warga'];
            // Ambil role aktif user ini
            $roles = [];
            $resRole = $koneksi->query("SELECT role FROM user_roles WHERE user_id IN (SELECT id_user FROM users WHERE warga_id=$user_id)");
            while ($r = $resRole->fetch_assoc()) {
                $roles[] = $r['role'];
            }
        ?>
        <tr>
            <td><?= $row['nama'] ?></td>
            <td>
                <?php
                // ambil username dari tabel users
                $getUsername = $koneksi->query("SELECT username, id_user FROM users WHERE warga_id=$user_id")->fetch_assoc();
                echo $getUsername ? $getUsername['username'] : "<i>Belum dibuat</i>";
                ?>
                <input type="hidden" name="user_id[<?= $user_id ?>]" value="<?= $getUsername['id_user'] ?? 0 ?>">
            </td>
            <?php foreach (['admin', 'panitia', 'kurban', 'warga'] as $r) { ?>
                <td><input type="checkbox" name="roles[<?= $user_id ?>][]" value="<?= $r ?>" <?= in_array($r, $roles) ? "checked" : "" ?>></td>
            <?php } ?>
        </tr>
        <?php } ?>
    </table>
    <br>
    <button type="submit">Simpan Perubahan</button>
</form>

<p><b>Info:</b> Panitia max 15 orang, Kurban max 9 orang</p>
