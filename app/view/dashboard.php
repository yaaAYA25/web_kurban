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
	<meta charset="UTF-8">
	<title>Dashboard - <?= htmlspecialchars($nama); ?></title>
	<style>
		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			display: flex;
			min-height: 100vh;
			background-color: #f9f9f9;
		}

		.sidebar {
			width: 250px;
			background: linear-gradient(to bottom, #2c3e50, #34495e);
			color: white;
			padding: 20px;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			position: fixed;
			height: 100vh;
		}

		.sidebar h3 {
			margin: 0 0 10px;
		}

		.sidebar p {
			font-size: 14px;
			color: #ccc;
		}

		.menu-item {
			margin-bottom: 15px;
		}

		.toggle-submenu {
			cursor: pointer;
			font-weight: bold;
			padding: 10px;
			background-color: #3b5160;
			border-radius: 5px;
			display: block;
		}

		.toggle-submenu .arrow {
			font-size: 12px;
			margin-left: 5px;
		}

		.submenu {
			display: none;
			margin-top: 5px;
			padding-left: 10px;
		}

		.submenu a {
			display: block;
			color: #ecf0f1;
			padding: 6px;
			margin: 4px 0;
			text-decoration: none;
			border-radius: 3px;
		}

		.submenu a:hover {
			background-color: #16a085;
		}

		.logout-btn {
			background-color: #e74c3c;
			color: white;
			padding: 10px;
			text-align: center;
			border-radius: 5px;
			text-decoration: none;
			font-weight: bold;
			margin-top: 20px;
		}

		.logout-btn:hover {
			background-color: #c0392b;
		}

		.content {
			flex: 1;
			padding: 30px;
			margin-left: 250px;
			background-color: #fff;
			min-height: 100vh;
		}

		.content h2 {
			margin-top: 0;
		}
	</style>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

	<div class="sidebar">
		<div>
			<h3>Halo, <?= htmlspecialchars($nama); ?></h3>
			<p>Role: <?= implode(', ', $roles); ?></p>

			<?php
			$menu_per_role = [
				'admin' => [
					'label' => 'Admin (Semua Fitur)',
					'items' => [
						['text' => 'Info Peran', 'page' => 'info_roles.php'],
						['text' => 'Ambil Daging', 'page' => 'pembagian_daging.php'],
						['text' => 'Kelola Warga', 'page' => 'kelola-warga.php'],
						['text' => 'Data Hewan', 'page' => 'info_hewan.php'],
						['text' => 'Distribusi', 'page' => 'info_pembagian.php'],
						['text' => 'Keuangan', 'page' => 'info_keuangan.php'],
					]
				],
				'warga' => [
					'label' => 'Warga',
					'items' => [
						['text' => 'Info Peran', 'page' => 'info_roles.php'],
						['text' => 'Ambil Daging', 'page' => 'pembagian_daging.php'],
					]
				],
				'panitia' => [
					'label' => 'Panitia',
					'items' => [
						['text' => 'Data Warga', 'page' => 'info_roles.php'],
						['text' => 'Data Hewan', 'page' => 'info_hewan.php'],
						['text' => 'Distribusi', 'page' => 'info_pembagian.php'],
						['text' => 'Keuangan', 'page' => 'info_keuangan.php'],
					]
				],
				'kurban' => [
					'label' => 'Pekurban',
					'items' => [
						['text' => 'Hewan Saya', 'page' => 'view/info_hewan.php'],
					]
				],
			];

			foreach ($menu_per_role as $role_key => $menu_data):
				if (in_array($role_key, $roles)):
			?>
					<div class="menu-item">
						<span class="toggle-submenu"><?= htmlspecialchars($menu_data['label']) ?> <span class="arrow">▼</span></span>
						<div class="submenu">
							<?php foreach ($menu_data['items'] as $item): ?>
								<a href="#" class="menu-link" data-page="<?= htmlspecialchars($item['page']) ?>">
									<?= htmlspecialchars($item['text']) ?>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
			<?php
				endif;
			endforeach;
			?>
		</div>

		<a href="login.php" class="logout-btn">Logout</a>
	</div>

	<div class="content" id="main-content">
		<h2>Selamat datang, <?= htmlspecialchars($nama); ?>!</h2>
		<p>Silakan pilih fitur dari sidebar sesuai peran Anda.</p>
	</div>

	<script>
		$(document).ready(function() {
			$('.toggle-submenu').click(function() {
				$(this).next('.submenu').slideToggle();
			});

			$('.menu-link').click(function(e) {
				e.preventDefault();
				const page = $(this).data('page');
				$('#main-content').load(page);
			});
		});
	</script>

</body>

</html>