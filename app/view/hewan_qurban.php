<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Input Hewan Qurban</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#06b6d4', // cyan-500
            dark: '#0f172a',    // slate-900
            light: '#f1f5f9'    // slate-100
          }
        }
      }
    }
  </script>
</head>
<body class="bg-light min-h-screen flex items-center justify-center px-4 font-sans">

  <div class="w-full max-w-lg bg-white shadow-xl rounded-3xl p-8 border border-cyan-100">
    <div class="text-center mb-6">
      <h2 class="text-3xl font-bold text-dark">Form Input Qurban</h2>
      <p class="text-primary text-sm">Masukkan data hewan qurban dengan lengkap</p>
    </div>

    <form action="../proses/proses_hewan.php" method="POST" class="space-y-5">
      
      <!-- Jenis Hewan -->
      <div>
        <label class="block text-dark font-medium mb-1">Jenis Hewan</label>
        <select name="jenis" required class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary transition">
          <option value="" disabled selected>Pilih jenis hewan</option>
          <option value="kambing">Kambing</option>
          <option value="sapi">Sapi</option>
        </select>
      </div>

      <!-- Jumlah -->
      <div>
        <label class="block text-dark font-medium mb-1">Jumlah</label>
        <input type="number" name="jumlah" required placeholder="Contoh: 2" class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary transition"/>
      </div>

      <!-- Total Berat -->
      <div>
        <label class="block text-dark font-medium mb-1">Total Berat (kg)</label>
        <input type="number" name="total_berat" required placeholder="Contoh: 150" class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary transition"/>
      </div>

      <!-- Biaya Total -->
      <div>
        <label class="block text-dark font-medium mb-1">Biaya Total (Rp)</label>
        <input type="number" step="0.01" name="biaya_total" required placeholder="Contoh: 5000000" class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary transition"/>
      </div>

      <!-- Tombol Simpan -->
      <div class="pt-2">
        <button type="submit" class="w-full bg-primary hover:bg-cyan-600 text-white font-semibold py-3 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg">
          Simpan Data
        </button>
      </div>

    </form>
  </div>

</body>
</html>
