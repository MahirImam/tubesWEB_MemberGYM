<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "db_gym"; // Nama DB baru

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if(mysqli_connect_errno()){
    echo "Koneksi gagal: ".mysqli_connect_error();
}

// 1. Tabel USERS (Otentikasi & Role)
$sql_users = "CREATE TABLE users (
    id_user INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(50) NOT NULL,
    role VARCHAR(10) NOT NULL DEFAULT 'user' 
)";

// 2. Tabel MEMBERS (Data Detail Member)
$sql_members = "CREATE TABLE members (
    id_member INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_user INT(6) UNSIGNED NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    no_hp VARCHAR(20),
    foto_profil VARCHAR(50),
    jenis_member VARCHAR(20) NOT NULL DEFAULT 'Basic',
    tgl_gabung DATE,
    tgl_kadaluarsa DATE
)";

// 3. Tabel MEMBERSHIP TIERS (Harga Paket)
$sql_tiers = "CREATE TABLE membership_tiers (
    id_tier INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_tier VARCHAR(20) NOT NULL,
    harga INT(10),
    deskripsi_fitur TEXT
)";

if (mysqli_query($conn, $sql_users) && mysqli_query($conn, $sql_members) && mysqli_query($conn, $sql_tiers)) {
    echo "Semua tabel (users, members, tiers) berhasil dibuat";
} else {
    echo "Gagal membuat tabel: ". mysqli_error($conn);
}
mysqli_close($conn);
?>