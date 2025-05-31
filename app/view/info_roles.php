<?php
session_start();
include "./../koneksi/koneksi.php";

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
    SELECT w.nama
    FROM user_roles ur
    JOIN users u ON ur.user_id = u.id_user
    JOIN warga w ON u.warga_id = w.id_warga
    WHERE ur.role = 'kurban'
    ORDER BY w.nama ASC
";
$result_kurban = $koneksi->query($sql_kurban);
$sql_total_warga = "SELECT COUNT(*) as total FROM warga";
$result_total_warga = $koneksi->query($sql_total_warga);
$total_warga = $result_total_warga->fetch_assoc()['total'] ?? 0;
$total_panitia = $result_panitia->num_rows;
$total_kurban = $result_kurban->num_rows;
$total_hewan_qurban = 2;  
$daging_terdistribusi = 85;
$daging_belum = 100 - $daging_terdistribusi;
$nama_panitia = [];
if ($result_panitia->num_rows > 0) {
    $result_panitia->data_seek(0);
    while ($row = $result_panitia->fetch_assoc()) {
        $nama_panitia[] = $row['nama'];
    }
}

$nama_kurban = [];
if ($result_kurban->num_rows > 0) {
    $result_kurban->data_seek(0);
    while ($row = $result_kurban->fetch_assoc()) {
        $nama_kurban[] = $row['nama'];
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9fafc;
    }
    .info-cards {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    .card {
        flex: 1;
        min-width: 150px;
        background: #fff;
        padding: 18px 16px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgb(0 0 0 / 0.05);
        text-align: center;
    }
    .card i {
        font-size: 28px;
        margin-bottom: 8px;
        color: #1a9cb8;
    }
    .card .label {
        font-size: 14px;
        color: #666;
    }
    .card .value {
        font-size: 24px;
        font-weight: 700;
        color: #222;
    }

    .dashboard-container {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
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
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.04);
        flex: 1 1 48%;
        min-width: 320px;
    }

    .role-section h2 {
        font-size: 18px;
        font-weight: 700;
        color: #333;
        margin-bottom: 14px;
        text-align: center;
        border-bottom: 2px solid #ddd;
        padding-bottom: 6px;
    }

    .role-table {
        width: 100%;
        border-collapse: collapse;
    }

    .role-table td {
        padding: 8px;
        border-top: 1px solid #e0e0e0;
        vertical-align: middle;
        font-size: 14px;
    }

    .role-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ccc;
    }

    .role-name {
        font-weight: 600;
        color: #222;
    }

    .role-label {
        font-size: 12px;
        color: #888;
    }

    .toggle-button-role {
        display: block;
        margin: 10px auto 0;
        text-align: center;
        background: none;
        border: none;
        color: #1a9cb8;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .toggle-button-role:hover {
        color: #10778a;
    }

    .extra-role {
        display: none;
        animation: fadeIn 0.3s ease-in-out;
    }

    .extra-role.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    canvas {
        width: 100% !important;
        max-height: 20px;
        margin-top: 20px;
    }
    .distribution-panel {
        flex: 1 1 280px;
        background: #fff;
        padding: 25px 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgb(0 0 0 / 0.07);
        text-align: center;
        min-width: 280px;
        height: fit-content;
    }
    .distribution-panel h2 {
        margin-bottom: 10px;
        font-weight: 700;
        color: #333;
    }
    .distribution-value {
        font-size: 24px;
        font-weight: 700;
        margin-top: 10px;
        color: #4caf50;
    }

    @media (max-width: 900px) {
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
        <div class="label">Total Pekurban</div>
        <div class="value"><?= $total_kurban ?></div>
    </div>
    <div class="card">
        <i class="fas fa-drumstick-bite"></i>
        <div class="label">Total Hewan Qurban</div>
        <div class="value"><?= $total_hewan_qurban ?></div>
    </div>
</div>

<div class="dashboard-container">
    <div class="role-graph-container">

        <!-- PANITIA -->
        <div class="role-section">
            <h2>Daftar Panitia</h2>
            <table class="role-table">
                <tbody>
                <?php
                $index = 0;
                $panitia_hidden = [];
                if ($result_panitia->num_rows === 0):
                    echo "<tr><td colspan='2'>Tidak ada panitia terdaftar.</td></tr>";
                else:
                    $result_panitia->data_seek(0);
                    while ($row = $result_panitia->fetch_assoc()):
                        $index++;
                        if ($index <= 5):
                ?>
                        <tr>
                            <td><img src="https://ui-avatars.com/api/?name=<?= urlencode($row['nama']) ?>&background=random&size=40" class="role-avatar"></td>
                            <td>
                                <div class="role-name"><?= htmlspecialchars($row['nama']) ?></div>
                                <div class="role-label">Panitia</div>
                            </td>
                        </tr>
                <?php
                        else:
                            $panitia_hidden[] = $row;
                        endif;
                    endwhile;
                endif;
                ?>
                </tbody>
            </table>

            <?php if (count($panitia_hidden) > 0): ?>
                <div class="extra-role" id="panitia-hidden">
                    <table class="role-table">
                        <tbody>
                        <?php foreach ($panitia_hidden as $row): ?>
                            <tr>
                                <td><img src="https://ui-avatars.com/api/?name=<?= urlencode($row['nama']) ?>&background=random&size=40" class="role-avatar"></td>
                                <td>
                                    <div class="role-name"><?= htmlspecialchars($row['nama']) ?></div>
                                    <div class="role-label">Panitia</div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button class="toggle-button-role" data-target="panitia-hidden">More</button>
            <?php endif; ?>

            <!-- Grafik Panitia -->
            <canvas id="chartPanitia"></canvas>
        </div>

        <!-- KURBAN -->
        <div class="role-section">
            <h2>Daftar Kurban</h2>
            <table class="role-table">
                <tbody>
                <?php
                $index = 0;
                $kurban_hidden = [];
                if ($result_kurban->num_rows === 0):
                    echo "<tr><td colspan='2'>Tidak ada warga yang kurban terdaftar.</td></tr>";
                else:
                    $result_kurban->data_seek(0);
                    while ($row = $result_kurban->fetch_assoc()):
                        $index++;
                        if ($index <= 5):
                ?>
                        <tr>
                            <td><img src="https://ui-avatars.com/api/?name=<?= urlencode($row['nama']) ?>&background=random&size=40" class="role-avatar"></td>
                            <td>
                                <div class="role-name"><?= htmlspecialchars($row['nama']) ?></div>
                                <div class="role-label">Kurban</div>
                            </td>
                        </tr>
                <?php
                        else:
                            $kurban_hidden[] = $row;
                        endif;
                    endwhile;
                endif;
                ?>
                </tbody>
            </table>

            <?php if (count($kurban_hidden) > 0): ?>
                <div class="extra-role" id="kurban-hidden">
                    <table class="role-table">
                        <tbody>
                        <?php foreach ($kurban_hidden as $row): ?>
                            <tr>
                                <td><img src="https://ui-avatars.com/api/?name=<?= urlencode($row['nama']) ?>&background=random&size=40" class="role-avatar"></td>
                                <td>
                                    <div class="role-name"><?= htmlspecialchars($row['nama']) ?></div>
                                    <div class="role-label">Kurban</div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button class="toggle-button-role" data-target="kurban-hidden">More</button>
            <?php endif; ?>
            <canvas id="chartKurban"></canvas>
        </div>

    </div>
    
    <div class="distribution-panel">
        <h2>Distribusi Daging</h2>
        <canvas id="chartDistribusi" width="200" height="200"></canvas>
        <div class="distribution-value"><?= $daging_terdistribusi ?>% Terdistribusi</div>
    </div>
</div>

<script>
    document.querySelectorAll('.toggle-button-role').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');
            const targetEl = document.getElementById(targetId);
            if (!targetEl) return;
            const isActive = targetEl.classList.toggle('active');
            button.textContent = isActive ? 'Less' : 'More';
        });
    });
    const panitiaLabels = <?= json_encode($nama_panitia) ?>;
    const kurbanLabels = <?= json_encode($nama_kurban) ?>;
    const ctxPanitia = document.getElementById('chartPanitia').getContext('2d');
    new Chart(ctxPanitia, {
        type: 'bar',
        data: {
            labels: panitiaLabels,
            datasets: [{
                label: 'Panitia',
                data: panitiaLabels.map(() => 1),
                backgroundColor: 'rgba(26, 156, 184, 0.7)',
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: { display: false, beginAtZero: true, max: 1 },
                y: { ticks: { font: { size: 12 } } }
            },
            plugins: { legend: { display: false } },
            responsive: true,
            maintainAspectRatio: false
        }
    });
    const ctxKurban = document.getElementById('chartKurban').getContext('2d');
    new Chart(ctxKurban, {
        type: 'bar',
        data: {
            labels: kurbanLabels,
            datasets: [{
                label: 'Kurban',
                data: kurbanLabels.map(() => 1),
                backgroundColor: 'rgba(102, 187, 106, 0.7)',
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: { display: false, beginAtZero: true, max: 1 },
                y: { ticks: { font: { size: 12 } } }
            },
            plugins: { legend: { display: false } },
            responsive: true,
            maintainAspectRatio: false
        }
    });
    const ctxDistribusi = document.getElementById('chartDistribusi').getContext('2d');
    new Chart(ctxDistribusi, {
        type: 'doughnut',
        data: {
            labels: ['Terkirim', 'Belum'],
            datasets: [{
                data: [<?= $daging_terdistribusi ?>, <?= $daging_belum ?>],
                backgroundColor: ['#4caf50', '#e0e0e0'],
                hoverOffset: 20,
                borderWidth: 0,
            }]
        },
        options: {
            cutout: '75%',
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            }
        }
    });
</script>
