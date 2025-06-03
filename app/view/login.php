<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Kurban</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .animate-fade-slide {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.6s ease-out;
    }

    .animate-show {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>
<body class="bg-cyan-100 flex items-center justify-center min-h-screen font-sans">

  <div id="login-box" class="animate-fade-slide flex w-full max-w-4xl shadow-2xl rounded-[2rem] overflow-hidden bg-white">
    
    <!-- LEFT: Welcome Section -->
    <div class="w-1/2 bg-cyan-500 text-white flex flex-col justify-center items-center px-10 py-16 rounded-tr-[6rem] rounded-br-[6rem]">
      <img src="https://cdn-icons-png.flaticon.com/512/1998/1998610.png" alt="Ikon Kurban" class="w-24 h-24 mb-6" />
      <h2 class="text-3xl font-bold mb-4 text-center">Sistem Kurban</h2>
      <p class="text-center text-white/80">Silakan masuk untuk mengakses distribusi dan data qurban</p>
    </div>

    <!-- RIGHT: Login Form -->
    <div class="w-1/2 p-10 bg-white">
      <h2 class="text-2xl font-bold mb-6 text-gray-700 text-center">Login</h2>
      <form action="../proses/aksi_login.php" method="POST" class="space-y-5">
        <div>
          <input type="text" name="username" placeholder="Username" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500" required>
        </div>
        <div>
          <input type="password" name="password" placeholder="Password" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500" required>
        </div>
        <div class="text-right">
          <a href="#" class="text-sm text-cyan-600 hover:underline">Lupa password?</a>
        </div>
        <button type="submit" name="login" class="w-full bg-cyan-600 text-white py-2 rounded hover:bg-cyan-700 transition">Login</button>
      </form>
    </div>
  </div>

  <script>
    window.addEventListener('DOMContentLoaded', () => {
      document.getElementById('login-box').classList.add('animate-show');
    });
  </script>
</body>
</html>
