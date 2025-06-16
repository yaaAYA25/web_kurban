<?php
session_start();
include "./../koneksi/koneksi.php";

// Ambil data panitia (misal ada kolom nik di warga)
$sql_panitia = "
  SELECT w.id_warga, w.nama, w.nik
  FROM user_roles ur
  JOIN users u ON ur.user_id = u.id_user
  JOIN warga w ON u.warga_id = w.id_warga
  WHERE ur.role = 'panitia'
  ORDER BY w.nama ASC
";
$result_panitia = $koneksi->query($sql_panitia);

// Ambil data peserta kurban
$sql_kurban = "
  SELECT w.id_warga, w.nama, w.nik
  FROM user_roles ur
  JOIN users u ON ur.user_id = u.id_user
  JOIN warga w ON u.warga_id = w.id_warga
  WHERE ur.role = 'kurban'
  ORDER BY w.nama ASC
";
$result_kurban = $koneksi->query($sql_kurban);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>ID Card Qurban</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @keyframes slideUp {
            0% {
                transform: translateY(40px);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .animate-slide-up {
            animation: slideUp 1s ease-out;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800">

    <!-- HEADER ANIMASI -->
    <section class="w-full bg-gradient-to-r from-cyan-700 to-cyan-300 py-16 text-white text-center shadow-lg rounded-b-3xl">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 animate-slide-up">Selamat Hari Raya Idul Adha</h1>
        <p class="text-lg md:text-xl animate-slide-up">Semoga Qurban Kita Diterima Allah SWT</p>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-12 space-y-16">

        <!-- SECTION PANITIA -->
        <div>
            <h2 class="text-2xl md:text-3xl font-semibold text-cyan-700 mb-6 text-center">👥 Panitia Qurban</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <?php while ($row = $result_panitia->fetch_assoc()): ?>
                    <div class="bg-white rounded-xl shadow-md p-6 flex flex-col items-center hover:shadow-xl transition-all duration-300 animate-slide-up">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($row['nama']) ?>&background=0D8ABC&color=fff&size=120"
                            alt="Avatar" class="w-24 h-24 rounded-full shadow mb-4">
                        <div class="text-center space-y-1">
                            <h3 class="text-lg font-bold"><?= htmlspecialchars($row['nama']) ?></h3>
                            <p class="text-sm text-gray-600">NIK: <?= htmlspecialchars($row['nik']) ?></p>
                            <span class="inline-block bg-cyan-100 text-cyan-700 text-xs font-medium px-3 py-1 rounded-full">Panitia</span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- SECTION PESERTA QURBAN -->
        <div>
            <h2 class="text-2xl md:text-3xl font-semibold text-green-700 mb-6 text-center">🐏 Warga yang Berkurban</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <?php while ($row = $result_kurban->fetch_assoc()): ?>
                    <div class="bg-white rounded-xl shadow-md p-6 flex flex-col items-center hover:shadow-xl transition-all duration-300 animate-slide-up">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($row['nama']) ?>&background=228B22&color=fff&size=120"
                            alt="Avatar" class="w-24 h-24 rounded-full shadow mb-4">
                        <div class="text-center space-y-1">
                            <h3 class="text-lg font-bold"><?= htmlspecialchars($row['nama']) ?></h3>
                            <p class="text-sm text-gray-600">NIK: <?= htmlspecialchars($row['nik']) ?></p>
                            <span class="inline-block bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">Peserta Qurban</span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

    </div>

</body>

</html>