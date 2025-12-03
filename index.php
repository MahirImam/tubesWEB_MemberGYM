<?php
session_start();
if(empty($_SESSION['username'])){
    echo "<script>alert('Anda harus login terlebih dahulu')</script>";
    echo "<meta http-equiv='refresh' content='0; url=login.php'>";
    exit();
}
$role = $_SESSION['role'];
$username = $_SESSION['username'];
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Dashboard | Member Gym</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>Manajemen Gym</header>
<div class="container">
    <aside>
        <ul class="menu">
            <li><a href="index.php">Dashboard</a></li>
            <?php if ($role == 'admin'): ?>
                <li><a href="members_crud.php">Kelola Member (Admin)</a></li>
                <li><a href="tiers_crud.php">Kelola Paket (Admin)</a></li>
            <?php else: ?>
                <li><a href="profil_member.php">Profil Saya</a></li>
                <li><a href="perpanjangan.php">Perpanjangan/Upgrade</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Keluar</a></li>
        </ul>
    </aside>
    <section class="main">
        <h1>Selamat Datang, <?= $username ?> (<?= strtoupper($role) ?>)</h1>
        <hr>
        <h2>Info Kesehatan dan Kebugaran Hari Ini</h2>
        <div class="info-box" style="background-color: #e3f2fd; padding: 15px; border-radius: 8px;">
            <p>Tubuh yang sehat berawal dari kebiasaan yang teratur. Berikut tips hari ini:</p>
            <ul>
                <li>**Tips Nutrisi:** Pastikan asupan protein cukup setelah latihan untuk perbaikan otot. Sumber terbaik: dada ayam, telur, atau whey protein.</li>
                <li>**Tips Hidrasi:** Minum minimal 3 liter air per hari untuk mendukung metabolisme dan energi.</li>
                <li>**Fokus Latihan:** Jangan lupakan *recovery*. Lakukan peregangan 15 menit setelah sesi berat.</li>
            </ul>
        </div>
        
        <?php if ($role == 'admin'): ?>
            <h3 style="margin-top: 20px;">Akses Admin Penuh Aktif</h3>
            <p>Anda memiliki hak akses untuk memanipulasi semua data member gym.</p>
        <?php endif; ?>

    </section>
</div>
</body>
</html>
