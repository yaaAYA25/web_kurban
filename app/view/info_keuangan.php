<?php
include "./../koneksi/koneksi.php";

// Ambil data untuk tabel
$sql = "SELECT tanggal, jenis, sumber, jumlah, keterangan, total_keuangan FROM keuangan ORDER BY tanggal ASC";
$result = $koneksi->query($sql);

// Hitung total masuk & keluar
$masuk = 0;
$keluar = 0;
$sql2 = "SELECT jenis, jumlah FROM keuangan";
$result2 = $koneksi->query($sql2);

while ($r = $result2->fetch_assoc()) {
    $jenis = strtolower($r['jenis']);
    $jumlah = floatval($r['jumlah']);
    if ($jenis === 'masuk') {
        $masuk += $jumlah;
    } elseif ($jenis === 'keluar') {
        $keluar += $jumlah;
    }
}

$saldo = $masuk - $keluar;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Informasi Keuangan Kurban</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-cyan-50 text-gray-800">

<div class="max-w-6xl mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold text-cyan-700 mb-6">Informasi Keuangan Kurban</h2>

    <!-- Tombol Input -->
    <div class="mb-6">
        <button onclick="toggleModal(true)"
                class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition">
            + Input Keuangan
        </button>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white border-l-8 border-green-400 shadow p-6 rounded-xl">
            <h3 class="text-lg font-semibold text-gray-600 mb-2">Total Uang Masuk</h3>
            <p class="text-2xl font-bold text-green-600">Rp <?= number_format($masuk, 2, ',', '.') ?></p>
        </div>
        <div class="bg-white border-l-8 border-red-400 shadow p-6 rounded-xl">
            <h3 class="text-lg font-semibold text-gray-600 mb-2">Total Uang Keluar</h3>
            <p class="text-2xl font-bold text-red-600">Rp <?= number_format($keluar, 2, ',', '.') ?></p>
        </div>
        <div class="bg-white border-l-8 border-cyan-500 shadow p-6 rounded-xl">
            <h3 class="text-lg font-semibold text-gray-600 mb-2">Total Saldo</h3>
            <p class="text-2xl font-bold text-cyan-600">Rp <?= number_format($saldo, 2, ',', '.') ?></p>
        </div>
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto bg-white rounded-xl shadow-md">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-cyan-600 text-white">
                <tr>
                    <th class="px-2 py-3 text-left font-semibold">Tanggal</th>
                    <th class="px-2 py-3 text-left font-semibold">Jenis</th>
                    <th class="px-4 py-3 text-left font-semibold">Sumber</th>
                    <th class="px-4 py-3 text-left font-semibold">Jumlah</th>
                    <th class="px-5 py-3 text-left font-semibold">Keterangan</th>
                    <th class="px-4 py-3 text-left font-semibold">Total Keuangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="hover:bg-cyan-50">
                        <td class="px-2 py-3"><?= htmlspecialchars($row['tanggal']) ?></td>
                        <td class="px-2 py-3" style="color: <?= $row['jenis'] == 'masuk' ? 'green' : 'red' ?>">
                            <?= ucfirst($row['jenis']) ?>
                        </td>
                        <td class="px-4 py-3"><?= htmlspecialchars($row['sumber']) ?></td>
                        <td class="px-4 py-3">Rp <?= number_format($row['jumlah'], 2, ',', '.') ?></td>
                        <td class="px-5 py-3"><?= htmlspecialchars($row['keterangan']) ?></td>
                        <td class="px-4 py-3 font-bold">Rp <?= number_format($row['total_keuangan'], 2, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Input Keuangan -->
<div id="modalKeuangan" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
  <div class="bg-white w-[95%] max-w-sm mx-auto rounded-xl shadow-xl p-5 relative animate__animated animate__fadeInUp">

    <!-- Tombol Tutup -->
    <button onclick="toggleModal(false)" class="absolute top-3 right-4 text-gray-500 hover:text-red-500 text-xl font-bold">&times;</button>

    <h3 class="text-lg font-semibold text-center text-cyan-700 mb-4">Input Keuangan</h3>

    <form action="../proses/proses_tambah_keuangan.php" method="post" class="space-y-3 text-sm">

      <div>
        <label for="tanggal" class="block mb-1 text-gray-600">Tanggal</label>
        <input type="date" name="tanggal" id="tanggal" required class="w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-1 focus:ring-cyan-600" />
      </div>

      <div>
        <label for="jenis" class="block mb-1 text-gray-600">Jenis</label>
        <select name="jenis" id="jenis" required class="w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-1 focus:ring-cyan-600">
          <option value="">-- Pilih --</option>
          <option value="masuk">Masuk</option>
          <option value="keluar">Keluar</option>
        </select>
      </div>

      <div>
        <label for="sumber" class="block mb-1 text-gray-600">Sumber</label>
        <input type="text" name="sumber" id="sumber" maxlength="100" required class="w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-1 focus:ring-cyan-600" />
      </div>

      <div>
        <label for="jumlah" class="block mb-1 text-gray-600">Jumlah</label>
        <input type="number" name="jumlah" id="jumlah" step="0.01" min="0.01" required class="w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-1 focus:ring-cyan-600" />
      </div>

      <div>
        <label for="keterangan" class="block mb-1 text-gray-600">Keterangan</label>
        <textarea name="keterangan" id="keterangan" rows="2" class="w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-1 focus:ring-cyan-600"></textarea>
      </div>

      <div class="pt-2 text-right">
        <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-5 py-2 rounded-md text-sm font-semibold transition-all">
          Simpan
        </button>
      </div>

    </form>
  </div>
</div>

<!-- Script Toggle Modal -->
<script>
    function toggleModal(show) {
        const modal = document.getElementById('modalKeuangan');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>

</body>
</html>
