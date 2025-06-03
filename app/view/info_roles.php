<?php
session_start();
include "./../koneksi/koneksi.php";

// Ambil panitia
$sql_panitia = "
    SELECT w.nama
    FROM user_roles ur
    JOIN users u ON ur.user_id = u.id_user
    JOIN warga w ON u.warga_id = w.id_warga
    WHERE ur.role = 'panitia'
    ORDER BY w.nama ASC
";
$result_panitia = $koneksi->query($sql_panitia);

$sql_kurban = "
    SELECT w.nama AS nama_warga, hq.jenis AS jenis_hewan
    FROM user_roles ur
    JOIN users u ON ur.user_id = u.id_user
    JOIN warga w ON u.warga_id = w.id_warga
    JOIN relasi_user_hewan ruh ON ruh.user_role_id = ur.id
    JOIN hewan_qurban hq ON ruh.hewan_qurban_id = hq.id
    WHERE ur.role = 'kurban'
    ORDER BY w.nama ASC
";
$result_kurban = $koneksi->query($sql_kurban);


// Ambil total warga (contoh)
$sql_total_warga = "SELECT COUNT(*) as total FROM warga";
$result_total_warga = $koneksi->query($sql_total_warga);
$total_warga = $result_total_warga->fetch_assoc()['total'] ?? 0;

// Hitung total panitia dan kurban dari data query sebelumnya
$total_panitia = $result_panitia->num_rows;
$total_kurban = $result_kurban->num_rows;

// Data dummy total hewan qurban dan distribusi
$total_hewan_qurban = 2;  // misal data statis atau bisa dari DB
$daging_terdistribusi = 85; // persen distribusi daging (dummy)
$daging_belum = 100 - $daging_terdistribusi;

// Simpan nama panitia dan kurban untuk chart
$nama_panitia = [];
if ($result_panitia->num_rows > 0) {
    $result_panitia->data_seek(0);
    while ($row = $result_panitia->fetch_assoc()) {
        $nama_panitia[] = $row['nama'];
    }
}

// Simpan nama kurban untuk chart
$nama_kurban = [];
if ($result_kurban->num_rows > 0) {
    $result_kurban->data_seek(0);
    while ($row = $result_kurban->fetch_assoc()) {
        $nama_kurban[] = $row['nama_warga'];
    }
}
?>

<!-- Chart.js & FontAwesome -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9fafc;
        /* margin: 0;
        padding: 20px; */
        color: #333;
    }
    .info-cards {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    .card {
        flex: 1 1 150px;
        background: #fff;
        padding: 18px 16px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgb(0 0 0 / 0.08);
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: default;
    }
    .card:hover {
        box-shadow: 0 8px 18px rgb(0 0 0 / 0.12);
        transform: translateY(-4px);
    }
    .card i {
        font-size: 30px;
        margin-bottom: 8px;
        color: #1a9cb8;
    }
    .card .label {
        font-size: 14px;
        color: #666;
        margin-bottom: 6px;
        font-weight: 600;
        letter-spacing: 0.02em;
    }
    .card .value {
        font-size: 28px;
        font-weight: 700;
        color: #222;
    }

    .dashboard-container {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
        align-items: flex-start;
    }

    .role-graph-container {
        flex: 2 1 600px;
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
        justify-content: space-between;
    }

    .role-section {
        background: linear-gradient(135deg, #f8fbfd, #e8f1f7);
        padding: 22px 24px;
        border-radius: 14px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.06);
        flex: 1 1 48%;
        min-width: 320px;
        display: flex;
        flex-direction: column;
    }

    .role-section h2 {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 18px;
        text-align: center;
        border-bottom: 3px solid #1a9cb8;
        padding-bottom: 8px;
        letter-spacing: 0.03em;
    }

    .role-table {
        width: 100%;
        border-collapse: collapse;
        flex-grow: 1;
        overflow-y: auto;
        max-height: 280px;
    }

    .role-table td {
        padding: 10px 8px;
        border-top: 1px solid #dfe6e9;
        vertical-align: middle;
        font-size: 15px;
    }

    .role-table tr:first-child td {
        border-top: none;
    }

    .role-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #a4c9d9;
        box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
        transition: transform 0.2s ease;
    }
    .role-avatar:hover {
        transform: scale(1.1);
        border-color: #1a9cb8;
        box-shadow: 0 2px 8px rgb(26 156 184 / 0.6);
    }

    .role-name {
        font-weight: 700;
        color: #34495e;
        user-select: none;
    }

    .role-label {
        font-size: 13px;
        color: #7f8c8d;
        user-select: none;
    }

    .toggle-button-role {
        display: block;
        margin: 14px auto 0;
        background: #1a9cb8;
        border: none;
        color: white;
        font-weight: 600;
        font-size: 14px;
        padding: 8px 22px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.25s ease, box-shadow 0.25s ease;
        user-select: none;
        box-shadow: 0 3px 7px rgb(26 156 184 / 0.5);
    }
    .toggle-button-role:hover {
        background-color: #10778a;
        box-shadow: 0 5px 15px rgb(16 119 138 / 0.7);
    }
    .toggle-button-role:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(26, 156, 184, 0.5);
    }

    .extra-role {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: max-height 0.5s ease, opacity 0.4s ease;
    }

    .extra-role.active {
        max-height: 10px; /* cukup besar agar muat semua konten */
        opacity: 1;
        /* margin-top: 10px; */
        transition: max-height 0.7s ease, opacity 0.6s ease;
    }

canvas {
    max-height: 20px;
    width: 150px; /* default canvas pendek */
    transition: width 0.4s ease;
}

canvas.expanded {
    width: 100%; /* saat di-expand jadi full lebar container */
    max-height: 100px; /* supaya terlihat proporsional */
}
    /* Sisi kanan panel untuk donut chart distribusi */
    .distribution-panel {
        flex: 1 1 280px;
        background: #fff;
        padding: 28px 24px;
        border-radius: 16px;
        box-shadow: 0 5px 16px rgb(0 0 0 / 0.1);
        text-align: center;
        min-width: 280px;
        height: fit-content;
        user-select: none;
    }
    .distribution-panel h2 {
        margin-bottom: 14px;
        font-weight: 700;
        color: #2c3e50;
        letter-spacing: 0.03em;
    }
    .distribution-value {
        font-size: 26px;
        font-weight: 700;
        margin-top: 12px;
        color: #4caf50;
        text-shadow: 0 1px 2px rgb(0 0 0 / 0.1);
    }

    @media (max-width: 100px) {
        .dashboard-container {
            flex-direction: column;
        }
        .role-graph-container {
            flex: 1 1 100%;
        }
        .distribution-panel {
            min-width: auto;
            margin-top: 30px;
        }
    }
</style>

<div class="info-cards">
    <div class="card">
        <i class="fas fa-users"></i>
        <div class="label">Total Warga</div>
        <div class="value"><?= $total_warga ?></div>
    </div>
    <div class="card">
        <i class="fas fa-users-cog"></i>
        <div class="label">Total Panitia</div>
        <div class="value"><?= $total_panitia ?></div>
    </div>
    <div class="card">
        <i class="fas fa-hand-holding-heart"></i>
        <div class="label">Total Hewan Qurban</div>
        <div class="value"><?= $total_hewan_qurban ?></div>
    </div>
</div>

<div class="dashboard-container">
    <div class="role-graph-container">
        <!-- Panel Panitia -->
        <section class="role-section" id="panel-panitia">
            <h2>Panitia</h2>
            <table class="role-table" id="table-panitia">
                <?php
                $count = 0;
                $maxVisible = 5;
                $result_panitia->data_seek(0);
                while ($row = $result_panitia->fetch_assoc()) {
                    $count++;
                    $extraClass = ($count > $maxVisible) ? 'extra-role' : '';
                    echo '<tr class="' . $extraClass . '">';
                    echo '<td><img class="role-avatar" src="./../assets/img/warga/' . htmlspecialchars($row['nama']) . '.png" alt="Avatar"></td>';
                    echo '<td><span class="role-name">' . htmlspecialchars($row['nama']) . '</span><br><span class="role-label">Panitia</span></td>';
                    echo '</tr>';
                }
                ?>
            </table>
            <?php if ($total_panitia > $maxVisible) : ?>
                <button class="toggle-button-role" id="toggle-panitia">Show More</button>
            <?php endif; ?>
            <canvas id="chartPanitia"></canvas>
        </section>

        <!-- Panel Kurban -->
        <section class="role-section" id="panel-kurban">
            <h2>Kurban</h2>
            <table class="role-table" id="table-kurban">
                <?php
                $count = 0;
                $result_kurban->data_seek(0);
                while ($row = $result_kurban->fetch_assoc()) {
                    $count++;
                    $extraClass = ($count > $maxVisible) ? 'extra-role' : '';
                    echo '<tr class="' . $extraClass . '">';
                    echo '<td><img class="role-avatar" src="./../assets/img/warga/' . htmlspecialchars($row['nama_warga']) . '.png" alt="Avatar"></td>';
                    echo '<td><span class="role-name">' . htmlspecialchars($row['nama_warga']) . '</span><br><span class="role-label">' . htmlspecialchars($row['jenis_hewan']) . '</span></td>';
                    echo '</tr>';
                }
                ?>
            </table>
            <?php if ($total_kurban > $maxVisible) : ?>
                <button class="toggle-button-role" id="toggle-kurban">Show More</button>
            <?php endif; ?>
            <canvas id="chartKurban"></canvas>
        </section>
    </div>

    <aside class="distribution-panel">
        <h2>Distribusi Daging Qurban</h2>
        <canvas id="chartDistribusi"></canvas>
        <div class="distribution-value"><?= $daging_terdistribusi ?>% Terdistribusi</div>
    </aside>
</div>

<script>
    // Toggle Show More/Less untuk Panitia
 document.getElementById('toggle-panitia')?.addEventListener('click', function() {
    const rows = document.querySelectorAll('#table-panitia .extra-role');
    const isActive = rows[0]?.classList.contains('active');
    rows.forEach(row => {
        row.classList.toggle('active', !isActive);
    });
    this.textContent = isActive ? 'Show More' : 'Show Less';

    // Toggle class expanded pada canvas panitia
    const canvasPanitia = document.getElementById('chartPanitia');
    canvasPanitia.classList.toggle('expanded', !isActive);
});


    // Toggle Show More/Less untuk Kurban
  document.getElementById('toggle-kurban')?.addEventListener('click', function() {
    const rows = document.querySelectorAll('#table-kurban .extra-role');
    const isActive = rows[0]?.classList.contains('active');
    rows.forEach(row => {
        row.classList.toggle('active', !isActive);
    });
    this.textContent = isActive ? 'Show More' : 'Show Less';

    // Toggle class expanded pada canvas kurban
    const canvasKurban = document.getElementById('chartKurban');
    canvasKurban.classList.toggle('expanded', !isActive);
});


    // Data dari PHP untuk chart Panitia
    const panitiaLabels = <?= json_encode($nama_panitia) ?>;
    const kurbanLabels = <?= json_encode($nama_kurban) ?>;

    // Chart Panitia (Bar horizontal)
    const ctxPanitia = document.getElementById('chartPanitia').getContext('2d');
    new Chart(ctxPanitia, {
        type: 'bar',
        data: {
            labels: panitiaLabels,
            datasets: [{
                label: 'Panitia',
                data: panitiaLabels.map(() => 1),
                backgroundColor: 'rgba(26, 156, 184, 0.75)',
                borderRadius: 6,
                barPercentage: 0.7
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: { display: false, beginAtZero: true },
                y: { ticks: { color: '#2c3e50', font: { weight: '600' } } }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: context => `1 orang`
                    }
                }
            }
        }
    });

    // Chart Kurban (Bar horizontal)
    const ctxKurban = document.getElementById('chartKurban').getContext('2d');
    new Chart(ctxKurban, {
        type: 'bar',
        data: {
            labels: kurbanLabels,
            datasets: [{
                label: 'Kurban',
                data: kurbanLabels.map(() => 1),
                backgroundColor: 'rgba(75, 192, 192, 0.75)',
                borderRadius: 6,
                barPercentage: 0.7
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: { display: false, beginAtZero: true },
                y: { ticks: { color: '#2c3e50', font: { weight: '600' } } }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: context => `1 orang`
                    }
                }
            }
        }
    });

    // Chart Distribusi Daging (Donut)
    const ctxDistribusi = document.getElementById('chartDistribusi').getContext('2d');
    new Chart(ctxDistribusi, {
        type: 'doughnut',
        data: {
            labels: ['Terdistribusi', 'Belum'],
            datasets: [{
                data: [<?= $daging_terdistribusi ?>, <?= $daging_belum ?>],
                backgroundColor: ['#4caf50', '#ddd'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
