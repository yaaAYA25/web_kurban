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
			background: #f3f4f6;
			color: #1f2937;
			padding: 20px;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			position: fixed;
			height: 100vh;
			box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
		}

		.sidebar h3 {
			margin: 0 0 10px;
			color: #111827;
		}

		.sidebar p {
			font-size: 14px;
			color: #6b7280;
		}

		.menu-item {
			margin-bottom: 15px;
		}

		.toggle-submenu {
			cursor: pointer;
			font-weight: 600;
			color: #1f2937;
			padding: 8px 12px;
			border-radius: 8px;
			display: flex;
			align-items: center;
			justify-content: space-between;
			background-color: transparent;
			transition: background-color 0.3s ease;
		}

		.toggle-submenu:hover {
			background-color: #e0f2fe;
		}

		.arrow svg {
			width: 16px;
			height: 16px;
			fill: #6b7280;
			transition: transform 0.3s ease;
		}

		.menu-item.open .arrow svg {
			transform: rotate(180deg);
		}

		.submenu {
			display: none;
			margin-top: 5px;
			padding-left: 10px;
		}

		.submenu a {
			display: block;
			color: #1f2937;
			padding: 6px 12px;
			margin: 4px 0;
			text-decoration: none;
			border-radius: 6px;
			font-size: 15px;
			transition: background-color 0.2s;
		}

		.submenu a:hover {
			background-color: #e0f2fe;
		}

		.logout-btn {
			background-color: #3b82f6;
			color: white;
			padding: 12px;
			text-align: center;
			border-radius: 10px;
			text-decoration: none;
			font-weight: 600;
			margin-top: 30px;
			display: block;
			transition: background-color 0.3s ease;
		}

		.logout-btn:hover {
			background-color: #2563eb;
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
					'label' => 'Admin',
					'items' => [
						['text' => 'Info Panitia dan Peserta', 'page' => 'info_roles.php'],
						['text' => 'Pembagian Daging', 'page' => 'pembagian_daging.php'],
						['text' => 'Data Warga', 'page' => 'kelola-warga.php'],
						['text' => 'Data Hewan', 'page' => 'info_hewan_edit.php'],
						['text' => 'Input Hewan', 'page' => 'hewan_qurban.php'],
						['text' => 'Info Pembagian', 'page' => 'info_pembagian.php'],
						['text' => 'Input Keuangan', 'page' => 'tambah_keuangan.php'],
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
						['text' => 'Input Keuangan', 'page' => 'tambah_keuangan.php'],
						['text' => 'QR Code', 'page' => 'qr_panitia.php?warga_id=' . $warga_id],
						['text' => 'Upload QR Code', 'page' => 'upload_baca_qr.php'],
					]
				],
				'kurban' => [
					'label' => 'Pekurban',
					'items' => [
						['text' => 'Info Pembagian', 'page' => 'info_pembagian.php'],
						['text' => 'Info Keuangan', 'page' => 'info_keuangan.php'],
						['text' => 'QR Code', 'page' => 'qr_qurban.php?warga_id=' . $warga_id],
					]
				],
			];

			foreach ($menu_per_role as $role_key => $menu_data) :
				if (in_array($role_key, $roles)) :
			?>
					<div class="menu-item">
						<span class="toggle-submenu">
							<?= htmlspecialchars($menu_data['label']) ?>
							<span class="arrow">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
								</svg>
							</span>
						</span>
						<div class="submenu">
							<?php foreach ($menu_data['items'] as $item) : ?>
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

		<a href="logout.php" class="logout-btn">Logout</a>
	</div>

	<div class="content" id="main-content">
		<h2>Selamat datang, <?= htmlspecialchars($nama); ?>!</h2>
		<p>Silakan pilih fitur dari sidebar sesuai peran Anda.</p>
	</div>

	<script>
		$(document).ready(function() {
			$('.toggle-submenu').click(function() {
				const menuItem = $(this).closest('.menu-item');
				menuItem.toggleClass('open');
				menuItem.find('.submenu').slideToggle();
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
