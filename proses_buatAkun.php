<?php
session_start();
include "config.php";

function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, $input);
}

// 1. Ambil Data Form
$username = sanitize($conn, $_POST['username']);
$password = sanitize($conn, $_POST['password']);
$nama_lengkap = sanitize($conn, $_POST['nama_lengkap']);
$no_hp = sanitize($conn, $_POST['no_hp']);
$jenis_member = sanitize($conn, $_POST['jenis_member']);

// Tentukan tanggal kadaluarsa (Contoh: 30 hari dari sekarang)
$tgl_gabung = date('Y-m-d');
$tgl_kadaluarsa = date('Y-m-d', strtotime('+30 days'));

// Data Foto Profil
$foto = $_FILES['foto_profil']['name'];
$lokasi = $_FILES['foto_profil']['tmp_name'];
$tipefile = $_FILES['foto_profil']['type'];
$ukuranfile = $_FILES['foto_profil']['size'];
$foto_nama_final = "";
$error = "";

// 2. Validasi Username (Cek duplikasi)
$check_user = mysqli_query($conn, "SELECT username FROM users WHERE username='$username'");
if (mysqli_num_rows($check_user) > 0) {
    $error = "Username sudah digunakan!";
}

// 3. Proses Upload Foto
if ($error == "" && !empty($foto)) {
    if ($tipefile != "image/jpeg" && $tipefile != "image/jpg" && $tipefile != "image/png") {
        $error = "Tipe file foto tidak didukung. Hanya JPEG, JPG, atau PNG.";
    } elseif ($ukuranfile >= 1000000) {
        $error = "Ukuran file foto lebih dari 1 MB.";
    } else {
        $ext = strrchr($foto, '.');
        $foto_nama_final = basename($foto, $ext) . time() . $ext;
        move_uploaded_file($lokasi, "assets/".$foto_nama_final);
    }
}

// 4. Eksekusi INSERT ke Dua Tabel
if ($error == "") {
    $sql_user = "INSERT INTO users (username, password, role) 
                 VALUES ('$username', '$password', 'user')";
    $insert_user = mysqli_query($conn, $sql_user);
    
    if ($insert_user) {
        $new_user_id = mysqli_insert_id($conn);
        
        $sql_member = "INSERT INTO members (id_user, nama_lengkap, no_hp, foto_profil, jenis_member, tgl_gabung, tgl_kadaluarsa) 
                       VALUES ('$new_user_id', '$nama_lengkap', '$no_hp', '$foto_nama_final', '$jenis_member', '$tgl_gabung', '$tgl_kadaluarsa')";
        $insert_member = mysqli_query($conn, $sql_member);
        
        if ($insert_member) {
            echo "<script>alert('Pendaftaran berhasil! Silakan Login.')</script>";
            echo "<meta http-equiv='refresh' content='0; url=login.php'>";
        } else {
            mysqli_query($conn, "DELETE FROM users WHERE id_user='$new_user_id'"); 
            $error = "Gagal membuat data member: " . mysqli_error($conn);
        }
    } else {
        $error = "Gagal membuat akun login: " . mysqli_error($conn);
    }
}

// 5. Notifikasi Error
if ($error != "") {
    echo "<script>alert('Pendaftaran Gagal: $error')</script>";
    echo "<meta http-equiv='refresh' content='3; url=buat_akun.php'>";
}

mysqli_close($conn);
?>