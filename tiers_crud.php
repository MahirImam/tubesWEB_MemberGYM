<?php
session_start();
include "config.php"; 

if(empty($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    echo "<script>alert('Akses Admin Ditolak!')</script>";
    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    exit();
}

// Query untuk mengambil semua data paket
$tiers_query = mysqli_query($conn, "SELECT * FROM membership_tiers ORDER BY harga ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Paket | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>Manajemen Paket Membership</header>
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
        <h1>Kelola Paket Membership</h1>
        
        <form action="tier_insert.php" method="post">
            <h2>Tambah Paket Baru</h2>
            
            <label for="nama_tier">Nama Paket (Cth: Gold, Basic):</label>
            <input type="text" name="nama_tier" required>
            
            <label for="harga">Harga (IDR):</label>
            <input type="number" name="harga" required>
            
            <label for="deskripsi_fitur">Deskripsi Fitur:</label>
            <input type="text" name="deskripsi_fitur">
            
            <div class="add-button">
                <button type="submit">Simpan Paket</button>
            </div>
        </form>

        <h3>Daftar Paket Aktif</h3>
        <table id="tier-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Paket</th>
                    <th>Harga (IDR)</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 0; while($data = mysqli_fetch_array($tiers_query)): $no++; ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?= $data['nama_tier'] ?></td>
                    <td><?= number_format($data['harga'], 0, ',', '.') ?></td>
                    <td><?= $data['deskripsi_fitur'] ?></td>
                    <td>
                        <a class="tombol edit" href="tier_edit.php?id=<?= $data['id_tier'] ?>">Edit</a>
                        <a class="tombol hapus" onclick="return confirm('Hapus paket <?= $data['nama_tier'] ?>?')"
                           href="tier_hapus.php?id=<?= $data['id_tier'] ?>">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; 
                if ($no == 0) { echo "<tr><td colspan='5' style='text-align:center;'>Belum ada paket membership.</td></tr>"; }
                ?>
            </tbody>
        </table>
    </section>
</div>
</body>
</html>