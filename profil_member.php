<?php
session_start();
include "config.php"; 

if(empty($_SESSION['username']) || $_SESSION['role'] != 'user'){
    echo "<script>alert('Akses Ditolak. Harap login sebagai Member.')</script>";
    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    exit();
}

$id_user = $_SESSION['id_user'];
// Query untuk mengambil data member dan user yang sedang login
$query = mysqli_query($conn, "SELECT 
    m.*, u.username, u.password as current_password_hash 
    FROM members m 
    JOIN users u ON m.id_user = u.id_user 
    WHERE m.id_user = '$id_user'");
$data = mysqli_fetch_array($query);

// Cek apakah data ditemukan
if (!$data) {
    echo "<script>alert('Data profil tidak ditemukan!')</script>";
    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    exit();
}

$tiers_query = mysqli_query($conn, "SELECT nama_tier FROM membership_tiers");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profil Saya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>Profil Member</header>
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
        <h1>Profil Saya</h1>
        
        <form id="profile-form" action="member_update_user.php" method="post" enctype="multipart/form-data">
            <h2>Detail Akun & Membership</h2>
            
            <input type="hidden" name="id_member" value="<?= $data['id_member'] ?>">
            <input type="hidden" name="id_user" value="<?= $id_user ?>">

            <label for="nama_lengkap">Nama Lengkap:</label>
            <input type="text" name="nama_lengkap" value="<?= $data['nama_lengkap'] ?>" required>
            
            <label for="no_hp">No HP:</label>
            <input type="text" name="no_hp" value="<?= $data['no_hp'] ?>">
            
            <label for="username">Username (Tidak dapat diubah):</label>
            <input type="text" value="<?= $data['username'] ?>" disabled style="background-color:#eee; border:none; border-radius: 25px;">
            
            <label for="password_new">Ganti Password Baru (Kosongkan jika tidak diubah):</label>
            <input type="password" name="password_new" placeholder="Masukkan password baru">

            <p style="margin-top: 20px; font-weight: bold; border-left: 3px solid #6495ED; padding-left: 10px;">
                Status Member: <?= $data['jenis_member'] ?> (Kadaluarsa: <?= date('d M Y', strtotime($data['tgl_kadaluarsa'])) ?>)
            </p>
            
            <label for="foto_profil">Foto Profil Saat Ini:</label>
            <?php if (!empty($data['foto_profil'])): ?>
                <img src="assets/<?= $data['foto_profil'] ?>" width="100" style="border-radius: 50%; object-fit: cover; margin-bottom: 10px;">
                <input type="hidden" name="foto_lama" value="<?= $data['foto_profil'] ?>">
            <?php else: ?>
                <p> Tidak ada foto saat ini.</p>
            <?php endif; ?>

            <label for="foto_profil_new">Ubah Foto Profil (Opsional):</label>
            <input type="file" name="foto_profil_new">
            
            <div class="add-button">
                <button type="submit">Simpan Perubahan Profil</button>
            </div>
        </form>
    </section>
</div>
</body>
</html>