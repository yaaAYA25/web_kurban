<?php
session_start();
include "./../koneksi/koneksi.php";

// Ambil data panitia
$sql_panitia = "
  SELECT w.id_warga, w.nama, w.nik
  FROM user_roles ur
  JOIN users u ON ur.user_id = u.id_user
  JOIN warga w ON u.warga_id = w.id_warga
  WHERE ur.role = 'panitia'
  ORDER BY w.nama ASC
";
$result_panitia = $koneksi->query($sql_panitia);

// Ambil data peserta kurban + jenis hewan
$sql_kurban = "
  SELECT 
    w.id_warga, 
    w.nama, 
    w.nik,
    h.jenis AS nama_hewan
  FROM user_roles ur
  JOIN users u ON ur.user_id = u.id_user
  JOIN warga w ON u.warga_id = w.id_warga
  LEFT JOIN relasi_user_hewan ruh ON ruh.user_role_id = ur.id
  LEFT JOIN hewan_qurban h ON ruh.hewan_qurban_id = h.id
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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

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
      animation: slideUp 0.7s ease-out both;
    }
  </style>
</head>

<body class="bg-gradient-to-br from-cyan-50 via-white to-white text-gray-800">

  <!-- HEADER -->
  <section class="w-full bg-gradient-to-r from-cyan-900 via-cyan-700 to-cyan-500 py-16 text-white text-center shadow-md rounded-b-3xl">
    <h1 class="text-5xl font-bold mb-2 animate-slide-up">Hari Raya Idul Adha 1445 H</h1>
    <p class="text-lg font-light animate-slide-up">Semoga Qurban Kita Diterima Allah SWT</p>
  </section>

  <main class="max-w-7xl mx-auto px-6 py-16 space-y-28">

    <!-- PANITIA SECTION -->
    <section>
      <h2 class="text-center text-3xl md:text-4xl font-bold text-cyan-800 mb-12 animate-slide-up">Panitia Qurban</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php while ($row = $result_panitia->fetch_assoc()): ?>
          <div class="bg-white rounded-2xl shadow-md p-5 text-center border border-gray-100 hover:shadow-xl transition duration-300 animate-slide-up">
            <div class="w-24 h-24 mx-auto rounded-full border-4 border-cyan-400 bg-white shadow-inner mb-4 p-1">
              <img src="https://ui-avatars.com/api/?name=<?= urlencode($row['nama']) ?>&background=ffffff&color=0D8ABC&size=120"
                alt="Avatar" class="rounded-full w-full h-full object-cover">
            </div>
            <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($row['nama']) ?></h3>
            <p class="text-sm text-gray-500 mb-2">NIK: <?= htmlspecialchars($row['nik']) ?></p>
            <span class="inline-block bg-cyan-100 text-cyan-800 text-xs font-semibold px-4 py-1 rounded-full">Panitia</span>
          </div>
        <?php endwhile; ?>
      </div>
    </section>

    <!-- PESERTA QURBAN SECTION -->
    <section>
      <h2 class="text-center text-3xl md:text-4xl font-bold text-cyan-800 mb-12 animate-slide-up">Peserta Qurban</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php while ($row = $result_kurban->fetch_assoc()): ?>
          <div class="bg-white rounded-2xl shadow-md p-5 text-center border border-gray-100 hover:shadow-xl transition duration-300 animate-slide-up">
            <div class="w-24 h-24 mx-auto rounded-full border-4 border-cyan-400 bg-white shadow-inner mb-4 p-1">
              <img src="https://ui-avatars.com/api/?name=<?= urlencode($row['nama']) ?>&background=ffffff&color=0D8ABC&size=120"
                alt="Avatar" class="rounded-full w-full h-full object-cover">
            </div>
            <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($row['nama']) ?></h3>
            <?php if (!empty($row['nama_hewan'])): ?>
              <p class="text-sm text-gray-700 font-medium mb-1">Kurban: <?= htmlspecialchars($row['nama_hewan']) ?></p>
            <?php endif; ?>

            <p class="text-sm text-gray-500 mb-2">NIK: <?= htmlspecialchars($row['nik']) ?></p>
            <span class="inline-block bg-cyan-100 text-cyan-800 text-xs font-semibold px-4 py-1 rounded-full">Peserta Qurban</span>
          </div>
        <?php endwhile; ?>
      </div>
    </section>

  </main>
</body>

</html>