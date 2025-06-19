<?php
session_start();
include '../koneksi/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: app/view/login.php');
    exit;
}

$username = $_SESSION['username'];

$queryUser = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($queryUser);
$user_id = $user['id_user'];
$warga_id = $user['warga_id'];

$queryWarga = mysqli_query($koneksi, "SELECT nama FROM warga WHERE id_warga = $warga_id");
$warga = mysqli_fetch_assoc($queryWarga);
$nama = $warga['nama'];

$queryRoles = mysqli_query($koneksi, "SELECT role FROM user_roles WHERE user_id = $user_id");
$roles = [];
while ($row = mysqli_fetch_assoc($queryRoles)) {
    $roles[] = $row['role'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - <?= htmlspecialchars($nama); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .fade-in {
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }
        .fade-in.show {
            opacity: 1;
        }
        #loading-logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #22d3ee;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 15px rgba(34, 211, 238, 0.7);
            animation: pulse-cyan 2s infinite;
        }
        @keyframes pulse-cyan {
            0%, 100% {
                box-shadow: 0 0 15px rgba(34, 211, 238, 0.7);
            }
            50% {
                box-shadow: 0 0 30px rgba(34, 211, 238, 1);
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gray-100 flex">

    <!-- Loading Screen -->
    <div id="loading-screen" class="fixed inset-0 z-50 flex flex-col items-center justify-center" style="background-color:#f5f7fa;">
        <div id="loading-logo" aria-label="Logo Qurban" role="img" title="Logo Qurban">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="70" height="70" aria-hidden="true" focusable="false" fill="white">
                <path d="M32 2C19 2 10 12 10 24c0 12 10 28 22 28s22-16 22-28c0-12-9-22-22-22zm0 48c-11 0-19-15-19-26 0-9 7-18 19-18s19 9 19 18c0 11-8 26-19 26z"/>
                <circle cx="22" cy="26" r="4" />
                <circle cx="42" cy="26" r="4" />
                <path d="M32 38c-5 0-8 4-8 4h16s-3-4-8-4z"/>
            </svg>
        </div>
        <p class="mt-6 text-lg font-semibold animate-bounce" style="color:#0e7490;">Memuat dashboard...</p>
    </div>

    <!-- Sidebar -->
    <div id="sidebar" class="w-64 bg-white shadow-lg p-5 flex flex-col justify-between fixed h-full fade-in">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Halo, <?= htmlspecialchars($nama); ?></h3>
            <p class="text-sm text-gray-500 mb-6">Role: <?= implode(', ', $roles); ?></p>

            <a href="#" class="menu-link active-dashboard text-sm text-gray-700 rounded-md px-3 py-2 block transition mb-4" data-page="dashboard_content.php">Dashboard</a>

            <?php
            $menu_per_role = [
                'admin' => [
                    'label' => 'Admin',
                    'items' => [
                        ['text' => 'Info Panitia dan Peserta', 'page' => 'info_roles.php'],
                        ['text' => 'Data Warga', 'page' => 'kelola-warga.php'],
                        ['text' => 'Data Hewan', 'page' => 'info_hewan_edit.php'],
                        ['text' => 'Info Pembagian', 'page' => 'info_pembagian.php'],
                        ['text' => 'Info Keuangan', 'page' => 'info_keuangan.php'],
                    ]
                ],
                'warga' => [
                    'label' => 'Warga',
                    'items' => [
                        ['text' => 'Info Panitia dan Peserta', 'page' => 'info_roles.php'],
                        ['text' => 'Info Hewan Qurban', 'page' => 'info_hewan.php'],
                        ['text' => 'QR Code', 'page' => 'qr_warga.php?warga_id=' . $warga_id],
                    ]
                ],
                'panitia' => [
                    'label' => 'Panitia',
                    'items' => [
                        ['text' => 'Info Pembagian', 'page' => 'info_pembagian.php'],
                        ['text' => 'Info Keuangan', 'page' => 'info_keuangan.php'],
                        ['text' => 'QR Code', 'page' => 'qr_panitia.php?warga_id=' . $warga_id],
                        ['text' => 'Upload QR Code', 'page' => 'upload_baca_qr.php'],
                    ]
                ],
                'kurban' => [
                    'label' => 'Pekurban',
                    'items' => [
                        ['text' => 'Info Pembagian', 'page' => 'info_pembagian.php'],
                        // ['text' => 'Info Keuangan', 'page' => 'info_keuangan.php'],
                        ['text' => 'QR Code', 'page' => 'qr_qurban.php?warga_id=' . $warga_id],
                    ]
                ],
            ];

            foreach ($menu_per_role as $role_key => $menu_data) :
                if (in_array($role_key, $roles)) :
            ?>
                <div class="mb-4">
                    <button class="toggle-submenu w-full flex justify-between items-center text-left px-3 py-2 font-semibold text-gray-700 hover:bg-cyan-200 rounded-md">
                        <?= htmlspecialchars($menu_data['label']) ?>
                        <svg class="w-4 h-4 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill="currentColor" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" />
                        </svg>
                    </button>
                    <div class="submenu mt-1 pl-4 hidden flex-col space-y-1">
                        <?php foreach ($menu_data['items'] as $item) : ?>
                            <a href="#" class="menu-link text-sm text-gray-700 hover:bg-cyan-200 rounded-md px-3 py-2 block transition" data-page="<?= htmlspecialchars($item['page']) ?>">
                                <?= htmlspecialchars($item['text']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; endforeach; ?>
        </div>

        <a href="logout.php" class="mt-6 bg-cyan-600 text-white text-center py-2 px-4 rounded-md hover:bg-cyan-700 transition">Logout</a>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="ml-64 flex-1 p-8 bg-white min-h-screen fade-in">
        <h2 class="text-2xl font-bold mb-2">Selamat datang, <?= htmlspecialchars($nama); ?>!</h2>
        <p class="text-gray-600">Silakan pilih fitur dari sidebar sesuai peran Anda.</p>
    </div>

    <script>
        $(document).ready(function () {
            // Loading hanya 0.5 detik
            setTimeout(function () {
                $('#loading-screen').fadeOut(300, function () {
                    $('#sidebar, #main-content').addClass('show');
                });
            }, 500);

            // Toggle submenu
            $('.toggle-submenu').click(function () {
                const submenu = $(this).next('.submenu');
                $('.submenu').not(submenu).slideUp(200);
                $('.toggle-submenu svg').not($(this).find('svg')).removeClass('rotate-180');
                submenu.slideToggle(200);
                $(this).find('svg').toggleClass('rotate-180');
            });

            // Load konten dinamis
            $('.menu-link').click(function (e) {
                e.preventDefault();
                const page = $(this).data('page');
                $('.menu-link').removeClass('bg-cyan-500 text-white font-semibold');
                $('.menu-link').addClass('text-gray-700');
                $(this).addClass('bg-cyan-500 text-white font-semibold');

                if ($(this).hasClass('active-dashboard')) {
                    $("#main-content").html(`
                        <h2 class="text-2xl font-bold mb-2">Selamat datang, <?= htmlspecialchars($nama); ?>!</h2>
                        <p class="text-gray-600">Silakan pilih fitur dari sidebar sesuai peran Anda.</p>
                    `);
                } else {
                    $('#main-content').load(page);
                }
            });
        });
    </script>
</body>
</html>
