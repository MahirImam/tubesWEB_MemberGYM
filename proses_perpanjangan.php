<?php
session_start();
include "config.php"; 

if(empty($_SESSION['username']) || $_SESSION['role'] != 'user'){
    echo "<script>alert('Akses Ditolak. Harap login sebagai Member.')</script>";
    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    exit();
}

function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, $input);
}

$id_member = sanitize($conn, $_POST['id_member']);
$current_expiry = sanitize($conn, $_POST['current_expiry']);
$new_tier = sanitize($conn, $_POST['new_tier']);
$duration_months = (int)$_POST['duration']; // Durasi dalam bulan

// 1. Tentukan tanggal awal perpanjangan
// Jika sudah kadaluarsa (current_expiry < hari ini), mulai dari hari ini.
// Jika belum kadaluarsa, mulai dari current_expiry.
$start_date = (strtotime($current_expiry) < strtotime(date('Y-m-d'))) ? date('Y-m-d') : $current_expiry;

// 2. Hitung tanggal kadaluarsa baru
$new_expiry = date('Y-m-d', strtotime("+$duration_months months", strtotime($start_date)));


// 3. Update data member
$sql = "UPDATE members SET 
        jenis_member = '$new_tier',
        tgl_kadaluarsa = '$new_expiry'
        WHERE id_member = '$id_member'";

$query = mysqli_query($conn, $sql);

if($query){
    echo "<script>alert('Perpanjangan/Upgrade berhasil! Masa aktif baru hingga ". date('d M Y', strtotime($new_expiry)) ."')</script>";
    echo "<meta http-equiv='refresh' content='0; url=renewal.php'>";
} else {
    echo "<script>alert('Gagal memproses perpanjangan: ". mysqli_error($conn) ."')</script>";
    echo "<meta http-equiv='refresh' content='3; url=renewal.php'>";
}
mysqli_close($conn);
?>