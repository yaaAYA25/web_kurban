<?php
session_start();
include "./../koneksi/koneksi.php";

// Ambil panitia
$sql_panitia = "
    SELECT w.nama
    FROM user_roles ur
    JOIN users u ON ur.user_id = u.id_user
    JOIN warga w ON u.warga_id = w.id_warga
    WHERE ur.role = 'panitia'
    ORDER BY w.nama ASC
";
$result_panitia = $koneksi->query($sql_panitia);

// Ambil kurban
$sql_kurban = "
    SELECT w.nama
    FROM user_roles ur
    JOIN users u ON ur.user_id = u.id_user
    JOIN warga w ON u.warga_id = w.id_warga
    WHERE ur.role = 'kurban'
    ORDER BY w.nama ASC
";
$result_kurban = $koneksi->query($sql_kurban);

// Data dummy
$tugas_list = ['Pencatat', 'Penyembelih', 'Distribusi', 'Kebersihan'];
$status_list = ['Hadir', 'Belum Datang', 'Izin'];
$hewan_list = ['Sapi', 'Kambing'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Panitia & Kurban</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="max-w-6xl mx-auto p-6 space-y-12">

    <!-- STYLED TABEL PANITIA -->
    <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-blue-100">
        <div class="bg-cyan-600 px-6 py-4">
            <h2 class="text-white text-lg font-semibold">Daftar Panitia</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left bg-white">
                <thead class="bg-cyan-100 text-cyan-800 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-3 border-b">No</th>
                        <th class="px-6 py-3 border-b">Nama Panitia</th>
                        <th class="px-6 py-3 border-b">Tugas</th>
                        <th class="px-6 py-3 border-b">Status Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-50">
                    <?php
                    $no = 1;
                    if ($result_panitia->num_rows === 0):
                        echo "<tr><td colspan='4' class='px-6 py-4 text-center text-gray-500'>Belum ada panitia.</td></tr>";
                    else:
                        while ($row = $result_panitia->fetch_assoc()):
                            $tugas = $tugas_list[array_rand($tugas_list)];
                            $status = $status_list[array_rand($status_list)];
                            $status_color = $status == 'Hadir' ? 'bg-green-100 text-green-700' : ($status == 'Belum Datang' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
                    ?>
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-6 py-3 text-gray-800"><?= $no++ ?></td>
                        <td class="px-6 py-3 flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($row['nama']) ?>&background=random&size=40" class="w-8 h-8 rounded-full object-cover">
                            <span class="font-medium text-gray-900"><?= htmlspecialchars($row['nama']) ?></span>
                        </td>
                        <td class="px-6 py-3 text-cyan-900"><?= $tugas ?></td>
                        <td class="px-6 py-3">
                            <span class="px-3 py-1 text-xs rounded-full font-semibold <?= $status_color ?>">
                                <?= $status ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- STYLED TABEL KURBAN -->
    <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-green-100">
        <div class="bg-cyan-600 px-6 py-4">
            <h2 class="text-white text-lg font-semibold">Daftar Warga Berkurban</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left bg-white">
                <thead class="bg-cyan-100 text-green-800 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-3 border-b">No</th>
                        <th class="px-6 py-3 border-b">Nama Kurban</th>
                        <th class="px-6 py-3 border-b">Jenis Hewan</th>
                        <th class="px-6 py-3 border-b">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-50">
                    <?php
                    $no = 1;
                    if ($result_kurban->num_rows === 0):
                        echo "<tr><td colspan='4' class='px-6 py-4 text-center text-gray-500'>Belum ada warga yang berkurban.</td></tr>";
                    else:
                        while ($row = $result_kurban->fetch_assoc()):
                            $jenis = $hewan_list[array_rand($hewan_list)];
                            $jumlah = rand(1, 3);
                    ?>
                    <tr class="hover:bg-green-50 transition">
                        <td class="px-6 py-3 text-gray-800"><?= $no++ ?></td>
                        <td class="px-6 py-3 flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($row['nama']) ?>&background=random&size=40" class="w-8 h-8 rounded-full object-cover">
                            <span class="font-medium text-gray-900"><?= htmlspecialchars($row['nama']) ?></span>
                        </td>
                        <td class="px-6 py-3 text-cyan-900"><?= $jenis ?></td>
                        <td class="px-6 py-3 text-cyan-900"><?= $jumlah ?></td>
                    </tr>
                    <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
