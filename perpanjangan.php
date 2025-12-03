<?php
session_start();
include "config.php"; 

if(empty($_SESSION['username']) || $_SESSION['role'] != 'user'){
    echo "<script>alert('Akses Ditolak. Harap login sebagai Member.')</script>";
    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    exit();
}

$id_user = $_SESSION['id_user'];
// Ambil data profil member
$member_query = mysqli_query($conn, "SELECT * FROM members WHERE id_user = '$id_user'");
$member_data = mysqli_fetch_array($member_query);
$current_tier = $member_data['jenis_member'];
$current_expiry = $member_data['tgl_kadaluarsa'];

// Ambil data semua paket
$tiers_query = mysqli_query($conn, "SELECT * FROM membership_tiers ORDER BY harga ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Perpanjangan Member</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>Perpanjangan dan Upgrade</header>
<div class="container">
    <aside>
        <ul class="menu">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="profil_member.php">Profil Saya</a></li>
            <li><a href="perpanjangan.php">Perpanjangan/Upgrade</a></li>
            <li><a href="logout.php">Keluar</a></li>
        </ul>
    </aside>

    <section class="main">
        <h1>Perpanjangan & Upgrade Membership</h1>

        <div style="background-color: #ffe0b2; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <p><strong>Paket Anda Saat Ini:</strong> <?= $current_tier ?></p>
            <p><strong>Masa Aktif Berakhir:</strong> <?= date('d M Y', strtotime($current_expiry)) ?></p>
            <?php if (strtotime($current_expiry) < strtotime(date('Y-m-d'))): ?>
                <p style="color: red; font-weight: bold;">STATUS: KADALUARSA! Harap segera perpanjang.</p>
            <?php endif; ?>
        </div>
        
        <form action="perpanjangan.php" method="post">
            <h2>Pilih Opsi Perpanjangan</h2>
            
            <input type="hidden" name="id_member" value="<?= $member_data['id_member'] ?>">
            <input type="hidden" name="current_expiry" value="<?= $current_expiry ?>">

            <label for="new_tier">Paket Baru/Perpanjangan:</label>
            <select name="new_tier" required>
                <option value="">-- Pilih Paket Baru --</option>
                <?php while($tier = mysqli_fetch_array($tiers_query)): ?>
                    <option value="<?= $tier['nama_tier'] ?>" data-price="<?= $tier['harga'] ?>">
                        <?= $tier['nama_tier'] ?> (Rp <?= number_format($tier['harga'], 0, ',', '.') ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="duration">Durasi Perpanjangan:</label>
            <select name="duration" id="duration" required>
                <option value="1">1 Bulan</option>
                <option value="3">3 Bulan</option>
                <option value="6">6 Bulan</option>
                <option value="12">12 Bulan</option>
            </select>

            <div class="add-button" style="margin-top: 20px;">
                <button type="submit">Proses Perpanjangan/Upgrade</button>
            </div>
        </form>
    </section>
</div>
</body>
</html>