<?php
session_start();
include "config.php"; 

if(empty($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    echo "<script>alert('Akses Admin Ditolak!')</script>";
    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    exit();
}

$tiers_query = mysqli_query($conn, "SELECT nama_tier FROM membership_tiers");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Member | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>Manajemen Member (Admin)</header>
<div class="container">
    <aside>
        <ul class="menu">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="members_crud.php">Kelola Member (Admin)</a></li>
            <li><a href="tiers_crud.php">Kelola Paket (Admin)</a></li>
            <li><a href="logout.php">Keluar</a></li>
        </ul>
    </aside>

    <section class="main">
        <h1>Kelola Data Member Gym</h1>
        
        <form id="member-form" action="member_insert.php" method="post" enctype="multipart/form-data">
            <h2>Tambah Member Baru</h2>
            
            <label for="username">Username (Otentikasi):</label>
            <input type="text" id="username" name="username" required
                oninvalid="this.setCustomValidity('Username wajib diisi!')" 
                oninput="setCustomValidity('')">
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required
                oninvalid="this.setCustomValidity('Password wajib diisi!')" 
                oninput="setCustomValidity('')">
            
            <label for="nama_lengkap">Nama Lengkap:</label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" required
                oninvalid="this.setCustomValidity('Nama Lengkap wajib diisi!')" 
                oninput="setCustomValidity('')">
            
            <label for="no_hp">No HP:</label>
            <input type="text" id="no_hp" name="no_hp">

            <label for="jenis_member">Jenis Member:</label>
            <select id="jenis_member" name="jenis_member" required
                oninvalid="this.setCustomValidity('Jenis Member wajib dipilih!')" 
                oninput="setCustomValidity('')">
                <option value="">-- Pilih Paket --</option>
                <?php while($tier = mysqli_fetch_array($tiers_query)): ?>
                    <option value="<?= $tier['nama_tier'] ?>"><?= $tier['nama_tier'] ?></option>
                <?php endwhile; ?>
            </select>
            
            <label for="tgl_kadaluarsa">Tanggal Kadaluarsa:</label>
            <input type="date" id="tgl_kadaluarsa" name="tgl_kadaluarsa" required
                oninvalid="this.setCustomValidity('Tanggal Kadaluarsa wajib diisi!')" 
                oninput="setCustomValidity('')">

            <label for="foto_profil">Foto Profil:</label>
            <input type="file" id="foto_profil" name="foto_profil">
            
            <div class="add-button">
                <button type="submit" id="submit-btn">Simpan Member</button>
                <button type="reset">Reset</button>
            </div>
        </form>

        <h3>Daftar Member Aktif</h3>
        <?php include "member_tampil.php"; ?> 

    </section>
</div>
</body>
</html>