<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pembagian Daging Qurban</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // Custom Tailwind config for cyan as primary
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              DEFAULT: '#06b6d4', // cyan-500
              dark: '#0891b2', // cyan-600
            },
          },
        },
      },
    }
  </script>
</head>

<body class="min-h-screen bg-gradient-to-br from-cyan-50 to-white flex items-center justify-center font-sans">

  <div class="bg-white shadow-2xl rounded-3xl p-10 w-full max-w-lg border border-cyan-100">
    <h1 class="text-3xl font-bold text-center text-primary mb-8 tracking-tight">
      Pembagian Daging Qurban
    </h1>

    <form action="../proses/proses_pembagian.php" method="POST" class="space-y-6">
      <!-- Pilihan Jenis -->
      <div>
        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">
          Pilih Jenis Hewan
        </label>
        <select name="jenis" id="jenis" required
          class="w-full rounded-xl border border-gray-300 shadow-sm focus:ring-2 focus:ring-primary dark focus:outline-none px-4 py-3 text-gray-700 bg-white transition duration-300 ease-in-out">
          <option value="">-- Pilih Jenis --</option>
          <option value="sapi">Sapi</option>
          <option value="kambing">Kambing</option>
        </select>
      </div>

      <!-- Tombol -->
      <div class="flex justify-end">
        <button type="submit"
          class="bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-xl shadow-md transform transition duration-300 ease-in-out active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
          Bagikan Sekarang
        </button>
      </div>
    </form>
  </div>

</body>

</html>
