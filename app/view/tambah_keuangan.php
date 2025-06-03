<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Data Keuangan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center p-4">

  <div class="bg-white/30 backdrop-blur-md shadow-xl rounded-2xl p-8 w-full max-w-2xl">
    <h2 class="text-3xl font-bold text-white text-center mb-6 drop-shadow-md">Tambah Data Keuangan</h2>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 font-medium text-sm shadow">
        <?= htmlspecialchars($_SESSION['success']); ?>
      </div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 font-medium text-sm shadow">
        <?= htmlspecialchars($_SESSION['error']); ?>
      </div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form action="../proses/proses_tambah_keuangan.php" method="post" class="grid grid-cols-1 md:grid-cols-2 gap-6">

      <div class="flex flex-col">
        <label for="tanggal" class="text-white font-medium mb-1">Tanggal</label>
        <input type="date" name="tanggal" id="tanggal" required
               class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
      </div>

      <div class="flex flex-col">
        <label for="jenis" class="text-white font-medium mb-1">Jenis</label>
        <select name="jenis" id="jenis" required
                class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
          <option value="">-- Pilih Jenis --</option>
          <option value="masuk">Masuk</option>
          <option value="keluar">Keluar</option>
        </select>
      </div>

      <div class="flex flex-col md:col-span-2">
        <label for="sumber" class="text-white font-medium mb-1">Sumber</label>
        <input type="text" name="sumber" id="sumber" maxlength="100" required
               class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
      </div>

      <div class="flex flex-col md:col-span-2">
        <label for="jumlah" class="text-white font-medium mb-1">Jumlah (Rp)</label>
        <input type="number" name="jumlah" id="jumlah" step="0.01" min="0.01" required
               class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
      </div>

      <div class="flex flex-col md:col-span-2">
        <label for="keterangan" class="text-white font-medium mb-1">Keterangan</label>
        <textarea name="keterangan" id="keterangan" rows="4"
                  class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"></textarea>
      </div>

      <div class="md:col-span-2 flex justify-end">
        <button type="submit"
                class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
          Simpan Data
        </button>
      </div>
    </form>
  </div>

</body>
</html>
