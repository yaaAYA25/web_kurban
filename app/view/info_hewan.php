<?php
include '../koneksi/koneksi.php';

$query = "SELECT * FROM hewan_qurban ORDER BY created_at DESC";
$result = $koneksi->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Info Hewan Qurban</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1a9cb8;
            --bg: #f9fafb;
            --text: #111827;
            --gray: #6b7280;
            --white: #ffffff;
            --shadow: rgba(0, 0, 0, 0.05);
        }

        * {
            box-sizing: border-box;
            /* font-family: 'Inter', sans-serif; */
        }

        body {
            /* margin: 0;
            padding: 40px; */
            background-color: var(--bg);
            color: var(--text);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
            color: var(--text);
        }

        table {
            width: 95%;
            margin: auto;
            border-collapse: collapse;
            background-color: var(--white);
            box-shadow: 0 8px 24px var(--shadow);
            border-radius: 12px;
            overflow: hidden;
        }

        thead {
            background-color: var(--primary);
            color: var(--white);
        }

        th, td {
            padding: 14px 12px;
            text-align: center;
            border-bottom: 1px solid #f1f1f1;
        }

        tr:last-child td {
            border-bottom: none;
        }

        th {
            font-weight: 500;
            font-size: 15px;
        }

        td {
            color: var(--gray);
            font-size: 14px;
        }

        .summary-container {
            width: 95%;
            margin: 30px auto 10px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            background: var(--white);
            box-shadow: 0 8px 24px var(--shadow);
            padding: 20px 30px;
            border-radius: 12px;
        }

        .summary-box {
            flex: 1;
            min-width: 150px;
        }

        .summary-box h4 {
            margin: 0;
            font-size: 16px;
            color: var(--primary);
        }

        .summary-box p {
            margin: 4px 0 0;
            font-weight: 600;
            color: var(--text);
            font-size: 14px;
        }

        .footer-note {
            text-align: center;
            margin-top: 30px;
            color: var(--gray);
            font-size: 13px;
        }

        .info-card {
            width: 95%;
            margin: 30px auto 0;
            padding: 25px 30px;
            background-color: #eef2ff;
            border-left: 6px solid var(--primary);
            border-radius: 12px;
            box-shadow: 0 8px 24px var(--shadow);
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .info-card-icon {
            font-size: 26px;
            color: var(--primary);
            margin-top: 2px;
        }

        .info-card-content h4 {
            margin: 0 0 6px;
            font-size: 16px;
            color: var(--primary);
        }

        .info-card-content p {
            margin: 0;
            font-size: 14px;
            color: var(--text);
        }

        @media (max-width: 768px) {
            table, th, td {
                font-size: 12px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

<h2>Informasi Hewan Qurban</h2>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Jenis</th>
            <th>Jumlah</th>
            <th>Total Berat (kg)</th>
            <th>Biaya Total (Rp)</th>
            <th>Tanggal Input</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $total_jumlah = 0;
        $total_berat = 0;
        $total_biaya = 0;

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . ucfirst($row['jenis']) . "</td>";
            echo "<td>" . $row['jumlah'] . "</td>";
            echo "<td>" . $row['total_berat'] . "</td>";
            echo "<td>Rp " . number_format($row['biaya_total'], 2, ',', '.') . "</td>";
            echo "<td>" . date('d-m-Y H:i', strtotime($row['created_at'])) . "</td>";
            echo "</tr>";

            $total_jumlah += $row['jumlah'];
            $total_berat += $row['total_berat'];
            $total_biaya += $row['biaya_total'];
        }
        ?>
    </tbody>
</table>

<div class="summary-container">
    <div class="summary-box">
        <h4>Total Hewan</h4>
        <p><?= $total_jumlah ?> ekor</p>
    </div>
    <div class="summary-box">
        <h4>Total Berat</h4>
        <p><?= $total_berat ?> kg</p>
    </div>
    <div class="summary-box">
        <h4>Total Biaya</h4>
        <p>Rp <?= number_format($total_biaya, 2, ',', '.') ?></p>
    </div>
</div>

<p class="footer-note">
    “Sesungguhnya kami telah memberikan kepadamu nikmat yang banyak, maka dirikanlah salat karena Tuhanmu dan berqurbanlah.”<br>
    <em>– QS. Al-Kautsar: 1-2</em>
</p>

<div class="info-card">
    <div class="info-card-icon">📘</div>
    <div class="info-card-content">
        <h4>Tahukah kamu?</h4>
        <p>Seekor sapi bisa digunakan untuk qurban 7 orang, sedangkan kambing hanya untuk 1 orang. Pastikan pencatatan data qurbanmu tepat dan terorganisir demi kelancaran distribusi.</p>
    </div>
</div>

</body>
</html>
