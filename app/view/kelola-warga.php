<?php
session_start();
include "./../koneksi/koneksi.php";

if (!in_array('admin', $_SESSION['roles'])) {
    die("Akses ditolak. Halaman ini hanya untuk Admin.");
}

$sql = "SELECT * FROM warga";
$query = $koneksi->query($sql);

$panitiaCount = $koneksi->query("SELECT COUNT(*) as total FROM user_roles WHERE role='panitia'")->fetch_assoc()['total'];
$kurbanCount = $koneksi->query("SELECT COUNT(*) as total FROM user_roles WHERE role='kurban'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Warga</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen px-6 py-10 font-sans">

<div class="max-w-7xl mx-auto">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Kelola Warga & Role</h2>

    <form action="../proses/simpan_role.php" method="POST" class="bg-white shadow-xl rounded-xl p-6">
        <div class="overflow-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-700 text-left">
                <tr>
                    <th class="p-4">Nama</th>
                    <th class="p-4">Username</th>
                    <th class="p-4">Admin</th>
                    <th class="p-4">Panitia</th>
                    <th class="p-4">Kurban</th>
                    <th class="p-4">Warga</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                <?php while ($row = $query->fetch_assoc()) {
                    $user_id = $row['id_warga'];
                    $roles = [];
                    $resRole = $koneksi->query("SELECT role FROM user_roles WHERE user_id IN (SELECT id_user FROM users WHERE warga_id=$user_id)");
                    while ($r = $resRole->fetch_assoc()) {
                        $roles[] = $r['role'];
                    }
                    ?>
                    <tr>
                        <td class="p-4 text-gray-900"><?= $row['nama'] ?></td>
                        <td class="p-4 text-gray-700">
                            <?php
                            $getUsername = $koneksi->query("SELECT username, id_user FROM users WHERE warga_id=$user_id")->fetch_assoc();
                            echo $getUsername ? $getUsername['username'] : "<i class='text-gray-400'>Belum dibuat</i>";
                            ?>
                            <input type="hidden" name="user_id[<?= $user_id ?>]" value="<?= $getUsername['id_user'] ?? 0 ?>">
                        </td>
                        <?php foreach (['admin', 'panitia', 'kurban', 'warga'] as $r): ?>
                            <?php if ($r === 'kurban'): ?>
                                <td class="p-4">
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox"
                                               name="roles[<?= $user_id ?>][]"
                                               value="<?= $r ?>"
                                               <?= in_array($r, $roles) ? "checked" : "" ?>
                                               onchange="handleKurbanCheckbox(this, <?= $kurbanCount ?>)"
                                               id="kurbanCheckbox_<?= $user_id ?>"
                                               class="accent-cyan-600 w-4 h-4">
                                        <select name="kategori_hewan[<?= $user_id ?>]"
                                                id="hewanSelect_<?= $user_id ?>"
                                                class="border-gray-300 rounded-md text-sm px-2 py-1"
                                                style="display: <?= in_array('kurban', $roles) ? 'inline-block' : 'none' ?>;">
                                            <option value="">-- Hewan --</option>
                                            <option value="sapi">Sapi</option>
                                            <option value="kambing">Kambing</option>
                                        </select>
                                    </div>
                                </td>
                            <?php elseif ($r === 'panitia'): ?>
                                <td class="p-4">
                                    <input type="checkbox"
                                           name="roles[<?= $user_id ?>][]"
                                           value="<?= $r ?>"
                                           <?= in_array($r, $roles) ? "checked" : "" ?>
                                           onchange="handlePanitiaCheckbox(this, <?= $panitiaCount ?>)"
                                           class="accent-cyan-600 w-4 h-4">
                                </td>
                            <?php else: ?>
                                <td class="p-4">
                                    <input type="checkbox"
                                           name="roles[<?= $user_id ?>][]"
                                           value="<?= $r ?>"
                                           <?= in_array($r, $roles) ? "checked" : "" ?>
                                           class="accent-cyan-600 w-4 h-4">
                                </td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="bg-cyan-600 text-white px-6 py-2 rounded-md shadow hover:bg-cyan-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>

    <div class="mt-8 text-sm">
        <p class="text-gray-600">
            <strong class="text-cyan-600">Info:</strong>
            <span class="inline-block ml-2">Panitia: <strong><?= $panitiaCount ?>/15</strong></span>,
            Kurban: <strong><?= $kurbanCount ?>/9</strong>
        </p>
    </div>
</div>

<!-- Modal Pop-up -->
<div id="popupModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl shadow-lg max-w-sm text-center">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Peringatan</h2>
        <p id="popupMessage" class="text-gray-600 mb-4">Pesan di sini</p>
        <button onclick="closePopup()"
                class="mt-2 bg-cyan-600 text-white px-4 py-2 rounded hover:bg-cyan-700">
            Oke, Mengerti
        </button>
    </div>
</div>

<script>
    function toggleHewanSelect(userId) {
        const checkbox = document.getElementById('kurbanCheckbox_' + userId);
        const select = document.getElementById('hewanSelect_' + userId);
        if (checkbox.checked) {
            select.style.display = 'inline-block';
        } else {
            select.style.display = 'none';
            select.selectedIndex = 0;
        }
    }

    function showPopup(message) {
        document.getElementById("popupMessage").innerText = message;
        document.getElementById("popupModal").classList.remove("hidden");
        document.getElementById("popupModal").classList.add("flex");
    }

    function closePopup() {
        document.getElementById("popupModal").classList.add("hidden");
        document.getElementById("popupModal").classList.remove("flex");
    }

    function handlePanitiaCheckbox(checkbox, panitiaCount) {
        if (checkbox.checked && panitiaCount >= 15) {
            checkbox.checked = false;
            showPopup("Jumlah panitia sudah mencapai batas maksimum (15 orang).");
        }
    }

    function handleKurbanCheckbox(checkbox, kurbanCount) {
        const userId = checkbox.id.split("_")[1];
        const select = document.getElementById("hewanSelect_" + userId);

        if (checkbox.checked) {
            if (kurbanCount >= 9) {
                checkbox.checked = false;
                select.style.display = "none";
                select.selectedIndex = 0;
                showPopup("Jumlah pekurban sudah mencapai batas maksimum (9 orang).");
            } else {
                select.style.display = "inline-block";
            }
        } else {
            select.style.display = "none";
            select.selectedIndex = 0;
        }
    }
</script>

</body>
</html>
