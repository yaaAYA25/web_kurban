<?php
include '../koneksi/koneksi.php';

$query = "SELECT * FROM hewan_qurban ORDER BY created_at DESC";
$result = $koneksi->query($query);

$total_hewan = 0;
$total_biaya = 0;
$jenis_hewan = [];
$data = [];

while ($row = $result->fetch_assoc()) {
  $total_hewan += $row['jumlah'];
  $total_biaya += $row['biaya_total'];
  $jenis_hewan[] = ucfirst($row['jenis']);
  $data[] = $row;
}
$jenis_hewan = array_unique($jenis_hewan);

$showSuccess = isset($_GET['success']) && $_GET['success'] == '1';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Data Hewan Qurban</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              DEFAULT: '#06b6d4',
              dark: '#0891b2',
            },
          },
        },
      },
    }
  </script>
</head>

<body class="bg-gray-50 font-sans text-gray-800">
  <div id="pageContent" class="max-w-6xl mx-auto py-10 px-6 transition-all duration-300">
    <h2 class="text-3xl font-bold text-primary mb-6 border-b-2 border-cyan-400 pb-2">Informasi Hewan Qurban</h2>

    <!-- Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white shadow-md rounded-xl p-6 border-l-4 border-primary">
        <h3 class="text-gray-500 text-sm uppercase">Total Hewan</h3>
        <p class="text-3xl font-bold mt-2"><?= $total_hewan ?></p>
      </div>
      <div class="bg-white shadow-md rounded-xl p-6 border-l-4 border-primary">
        <h3 class="text-gray-500 text-sm uppercase">Total Biaya</h3>
        <p class="text-2xl font-bold text-primary mt-2">Rp <?= number_format($total_biaya, 2, ',', '.') ?></p>
      </div>
      <div class="bg-white shadow-md rounded-xl p-6 border-l-4 border-primary">
        <h3 class="text-gray-500 text-sm uppercase">Jenis Hewan</h3>
        <p class="text-lg font-semibold mt-2"><?= implode(', ', $jenis_hewan) ?></p>
      </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="mb-8 flex gap-4">
      <button onclick="toggleModal()"
        class="px-4 py-2 bg-white border border-primary text-primary font-semibold rounded-xl shadow-md transition text-sm hover:bg-primary hover:text-white">
        Pembagian Daging
      </button>
      <button onclick="toggleInputModal()"
        class="px-4 py-2 bg-white border border-primary text-primary font-semibold rounded-xl shadow-md transition text-sm hover:bg-primary hover:text-white">
        Input Hewan
      </button>
    </div>

    <!-- Tabel Hewan Qurban -->
    <div class="overflow-x-auto shadow-lg rounded-xl bg-white">
      <table class="min-w-full text-sm text-center border-separate border-spacing-y-2">
        <thead class="bg-primary text-white">
          <tr>
            <th class="px-4 py-3 rounded-tl-xl">No</th>
            <th class="px-4 py-3">Jenis</th>
            <th class="px-4 py-3">Jumlah</th>
            <th class="px-4 py-3">Total Berat (kg)</th>
            <th class="px-4 py-3">Biaya Total</th>
            <th class="px-4 py-3">Tanggal Input</th>
            <th class="px-4 py-3 rounded-tr-xl">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1;
          foreach ($data as $row): ?>
            <tr class="bg-white hover:bg-cyan-50 shadow-sm rounded">
              <td class="px-4 py-3 font-medium"><?= $no++ ?></td>
              <td class="px-4 py-3"><?= ucfirst($row['jenis']) ?></td>
              <td class="px-4 py-3"><?= $row['jumlah'] ?></td>
              <td class="px-4 py-3"><?= $row['total_berat'] ?></td>
              <td class="px-4 py-3 text-primary font-semibold">Rp <?= number_format($row['biaya_total'], 2, ',', '.') ?></td>
              <td class="px-4 py-3"><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
              <td class="px-4 py-3 space-x-2">
                <button onclick='openEditModal(<?= json_encode($row) ?>)' class="text-primary hover:underline">Edit</button>
                <a href="../proses/proses_hapus_hewan.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin hapus?')" class="text-red-600 hover:underline">Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Pembagian Daging -->
  <div id="popupModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-3xl p-10 w-full max-w-lg relative shadow-xl border border-primary">
      <button onclick="toggleModal()" class="absolute top-4 right-5 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>
      <h1 class="text-2xl font-bold text-center text-primary mb-6">Pembagian Daging Qurban</h1>
      <form action="../proses/proses_pembagian.php" method="POST" class="space-y-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Jenis Hewan</label>
          <select name="jenis" required class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-primary bg-white text-gray-700">
            <option value="">-- Pilih Jenis --</option>
            <?php foreach ($jenis_hewan as $j): ?>
              <option value="<?= strtolower($j) ?>"><?= ucfirst($j) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="flex justify-end">
          <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-xl shadow-md transition text-sm">
            Bagikan Sekarang
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Input Hewan -->
  <div id="popupInputModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-3xl p-10 w-full max-w-lg relative shadow-xl border border-primary">
      <button onclick="toggleInputModal()" class="absolute top-4 right-5 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>
      <h1 class="text-2xl font-bold text-center text-primary mb-6">Input Hewan Qurban</h1>
      <form action="../proses/proses_hewan.php" method="POST" class="space-y-5">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Hewan</label>
          <select name="jenis" required class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-primary">
            <option value="" disabled selected>Pilih jenis hewan</option>
            <option value="kambing">Kambing</option>
            <option value="sapi">Sapi</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
          <input type="number" name="jumlah" required class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-primary" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Total Berat (kg)</label>
          <input type="number" name="total_berat" required class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-primary" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Biaya Total</label>
          <input type="number" name="biaya_total" step="0.01" required class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-primary" />
        </div>
        <div class="text-right">
          <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-xl shadow-md transition text-sm">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Edit Hewan -->
  <div id="popupEditModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-3xl p-10 w-full max-w-lg relative shadow-xl border border-cyan-500">
      <button onclick="toggleEditModal()" class="absolute top-4 right-5 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>
      <h1 class="text-2xl font-bold text-center text-cyan-600 mb-6">Edit Data Hewan Qurban</h1>
      <form id="editForm" method="POST" action="../proses/proses_edit_hewan.php" class="space-y-5">
        <input type="hidden" name="id" id="edit_id" />
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Hewan</label>
          <select name="jenis" id="edit_jenis" required class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-cyan-500">
            <option value="kambing">Kambing</option>
            <option value="sapi">Sapi</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
          <input type="number" name="jumlah" id="edit_jumlah" required class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-cyan-500" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Total Berat (kg)</label>
          <input type="number" name="total_berat" id="edit_total_berat" required class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-cyan-500" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Biaya Total</label>
          <input type="number" name="biaya_total" id="edit_biaya_total" required class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-cyan-500" />
        </div>
        <div class="text-right">
          <button type="submit" name="update" class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md transition text-sm">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Script Modal -->
  <script>
    function toggleModal() {
      const modal = document.getElementById('popupModal');
      const page = document.getElementById('pageContent');
      modal.classList.toggle('hidden');
      page.classList.toggle('blur-sm');
      document.body.style.overflow = modal.classList.contains('hidden') ? '' : 'hidden';
    }

    function toggleInputModal() {
      const modal = document.getElementById('popupInputModal');
      const page = document.getElementById('pageContent');
      modal.classList.toggle('hidden');
      page.classList.toggle('blur-sm');
      document.body.style.overflow = modal.classList.contains('hidden') ? '' : 'hidden';
    }

    function toggleEditModal() {
      const modal = document.getElementById('popupEditModal');
      const page = document.getElementById('pageContent');
      modal.classList.toggle('hidden');
      page.classList.toggle('blur-sm');
      document.body.style.overflow = modal.classList.contains('hidden') ? '' : 'hidden';
    }

    function openEditModal(data) {
      document.getElementById('edit_id').value = data.id;
      document.getElementById('edit_jenis').value = data.jenis.toLowerCase();
      document.getElementById('edit_jumlah').value = data.jumlah;
      document.getElementById('edit_total_berat').value = data.total_berat;
      document.getElementById('edit_biaya_total').value = data.biaya_total;
      toggleEditModal();
    }

    window.onload = function() {
      const showSuccess = <?= $showSuccess ? 'true' : 'false' ?>;
      if (showSuccess) {
        alert('Berhasil diproses.');
        const url = new URL(window.location);
        url.searchParams.delete('success');
        history.replaceState(null, '', url.toString());
      }
    };
  </script>
</body>

</html>