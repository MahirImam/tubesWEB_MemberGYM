<?php
session_start();
include "config.php";

if(empty($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    echo "<script>alert('Akses Admin Ditolak!')</script>";
    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    exit();
}

function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, $input);
}

// 1. Ambil Data Form
$id_member = sanitize($conn, $_POST['id_member']);
$id_user = sanitize($conn, $_POST['id_user']);
$nama_lengkap = sanitize($conn, $_POST['nama_lengkap']);
$no_hp = sanitize($conn, $_POST['no_hp']);
$jenis_member = sanitize($conn, $_POST['jenis_member']);
$tgl_kadaluarsa = sanitize($conn, $_POST['tgl_kadaluarsa']);
$password_new = trim($_POST['password_new']); 
$foto_lama = sanitize($conn, $_POST['foto_lama'] ?? '');

$foto_nama_final = $foto_lama; 
$error = "";

// 2. Proses Upload Foto Baru (Sama seperti member_update_user.php)
if (isset($_FILES['foto_profil_new']) && $_FILES['foto_profil_new']['error'] == 0) {
    // Logika upload foto...
    $foto = $_FILES['foto_profil_new']['name'];
    $lokasi = $_FILES['foto_profil_new']['tmp_name'];
    $tipefile = $_FILES['foto_profil_new']['type'];
    $ukuranfile = $_FILES['foto_profil_new']['size'];

    if ($tipefile != "image/jpeg" && $tipefile != "image/jpg" && $tipefile != "image/png") {
        $error = "Tipe file foto baru tidak didukung.";
    } elseif ($ukuranfile >= 1000000) {
        $error = "Ukuran file foto baru lebih dari 1 MB.";
    } else {
        if (!empty($foto_lama) && file_exists("assets/".$foto_lama)) {
             unlink("assets/".$foto_lama);
        }
        
        $ext = strrchr($foto, '.');
        $foto_nama_final = "admin_edit_".$id_user."_".time() . $ext;
        move_uploaded_file($lokasi, "assets/".$foto_nama_final);
    }
}

// 3. Eksekusi Update
if ($error == "") {
    // --- UPDATE TABEL MEMBERS ---
    $sql_member = "UPDATE members SET 
                   nama_lengkap='$nama_lengkap', 
                   no_hp='$no_hp', 
                   jenis_member='$jenis_member',
                   tgl_kadaluarsa='$tgl_kadaluarsa', 
                   foto_profil='$foto_nama_final'
                   WHERE id_member='$id_member'";
    $update_member = mysqli_query($conn, $sql_member);
    
    // --- UPDATE TABEL USERS (jika password baru diisi) ---
    $update_user = true;
    if (!empty($password_new)) {
        $sql_user = "UPDATE users SET 
                     password='".sanitize($conn, $password_new)."'
                     WHERE id_user='$id_user'";
        $update_user = mysqli_query($conn, $sql_user);
    }

    if ($update_member && $update_user) {
        echo "<script>alert('Data member berhasil diperbarui!')</script>";
        echo "<meta http-equiv='refresh' content='0; url=members_crud.php'>";
    } else {
        $error = "Gagal memperbarui data: " . mysqli_error($conn);
    }
}

// 4. Notifikasi Error
if ($error != "") {
    echo "<script>alert('Update Gagal: $error')</script>";
    echo "<meta http-equiv='refresh' content='3; url=members_crud.php'>";
}

mysqli_close($conn);
?>