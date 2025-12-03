<?php
session_start();
include "config.php"; 

$tiers_query = mysqli_query($conn, "SELECT nama_tier FROM membership_tiers");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Member Baru</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <section class="login-box" style="flex-basis: 500px;">
        <h2>Formulir Pendaftaran Member</h2>
        <form method="post" action="proses_buatAkun.php" enctype="multipart/form-data">
            
            <label for="username">Username (untuk Login):</label>
            <input type="text" placeholder="username" name="username" required 
                oninvalid="this.setCustomValidity('Ups! Username wajib diisi.')" 
                oninput="setCustomValidity('')">
            
            <label for="password">Password:</label>
            <input type="password" placeholder="password" name="password" required
                oninvalid="this.setCustomValidity('Ups! Password wajib diisi.')" 
                oninput="setCustomValidity('')">
            
            <label for="nama_lengkap">Nama Lengkap:</label>
            <input type="text" placeholder="Nama Lengkap Anda" name="nama_lengkap" required
                oninvalid="this.setCustomValidity('Ups! Nama Lengkap wajib diisi.')" 
                oninput="setCustomValidity('')">
            
            <label for="no_hp">No HP:</label>
            <input type="text" placeholder="Nomor Telepon" name="no_hp">

            <label for="jenis_member">Pilih Paket Member:</label>
            <select name="jenis_member" required 
                oninvalid="this.setCustomValidity('Ups! Paket member wajib dipilih.')" 
                oninput="setCustomValidity('')">
                <option value="">-- Pilih Paket --</option>
                <?php while($tier = mysqli_fetch_array($tiers_query)): ?>
                    <option value="<?= $tier['nama_tier'] ?>"><?= $tier['nama_tier'] ?></option>
                <?php endwhile; ?>
            </select>
            
            <label for="foto_profil">Foto Profil (Opsional):</label>
            <input type="file" name="foto_profil">
            
            <input type="submit" value="DAFTAR SEKARANG">
        </form>
        <div style="text-align: center; margin-top: 10px;">
            <p>Sudah punya akun? <a href="login.php" style="color: #6495ED;">Login di sini</a></p>
        </div>
    </section>
</div>
</body>
</html>