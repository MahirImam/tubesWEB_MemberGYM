<?php
session_start();
include "config.php"; 

if(empty($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    echo "<script>alert('Akses Admin Ditolak!')</script>";
    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    exit();
}

$id_member = $_GET['id'] ?? null;
if (!$id_member) {
    header('location: members_crud.php');
    exit();
}

// 1. Ambil data member yang akan diedit
$query = mysqli_query($conn, "SELECT m.*, u.username FROM members m JOIN users u ON m.id_user = u.id_user WHERE m.id_member = '$id_member'");
$data = mysqli_fetch_array($query);

// 2. Ambil semua paket membership
$tiers_query = mysqli_query($conn, "SELECT nama_tier FROM membership_tiers");

if (!$data) {
    echo "<script>alert('Data member tidak ditemukan!')</script>";
    echo "<meta http-equiv='refresh' content='0; url=members_crud.php'>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Member | Admin</title>
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
        <h1>Edit Data Member: <?= $data['nama_lengkap'] ?></h1>
        
        <form action="member_update.php" method="post" enctype="multipart/form-data">
            
            <input type="hidden" name="id_member" value="<?= $data['id_member'] ?>">
            <input type="hidden" name="id_user" value="<?= $data['id_user'] ?>">
            <input type="hidden" name="foto_lama" value="<?= $data['foto_profil'] ?>">

            <label for="username">Username (Tidak dapat diubah):</label>
            <input type="text" value="<?= $data['username'] ?>" disabled style="background-color:#eee;">

            <label for="nama_lengkap">Nama Lengkap:</label>
            <input type="text" name="nama_lengkap" value="<?= $data['nama_lengkap'] ?>" required>
            
            <label for="no_hp">No HP:</label>
            <input type="text" name="no_hp" value="<?= $data['no_hp'] ?>">
            
            <label for="password_new">Ganti Password (Kosongkan jika tidak diubah):</label>
            <input type="password" name="password_new" placeholder="Masukkan password baru">

            <label for="jenis_member">Jenis Member:</label>
            <select name="jenis_member">
                <?php while($tier = mysqli_fetch_array($tiers_query)): ?>
                    <option value="<?= $tier['nama_tier'] ?>" <?= ($tier['nama_tier'] == $data['jenis_member']) ? 'selected' : '' ?>>
                        <?= $tier['nama_tier'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
            
            <label for="tgl_kadaluarsa">Tanggal Kadaluarsa:</label>
            <input type="date" name="tgl_kadaluarsa" value="<?= $data['tgl_kadaluarsa'] ?>" required>
            
            <label for="foto_profil_new">Ubah Foto Profil (Opsional):</label>
            <?php if (!empty($data['foto_profil'])): ?>
                <p>Foto saat ini: <img src="assets/<?= $data['foto_profil'] ?>" width="50" height="50" style="border-radius: 50%; object-fit: cover;"></p>
            <?php else: ?>
                <p>Tidak ada foto saat ini.</p>
            <?php endif; ?>
            <input type="file" name="foto_profil_new">
            
            <div class="add-button">
                <button type="submit">Simpan Perubahan</button>
                <a href="members_crud.php" style="color: black; margin-left: 10px;">Kembali</a>
            </div>
        </form>
    </section>
</div>
</body>
</html>