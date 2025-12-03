<?php
session_start();
include "config.php"; 

$username = $_POST['username'];
$password = $_POST['password']; 

$query = mysqli_query($conn, "SELECT id_user, username, role FROM users WHERE 
                                username='$username' AND password='$password'");
$data = mysqli_fetch_array($query);

if(empty($username) || empty($password)){ 
    echo "<script>alert('Username dan Password belum diisi')</script>";
    echo "<meta http-equiv='refresh' content='1;url=login.php'>";

} else if (mysqli_num_rows($query) == 1) { 
    
    $_SESSION['login'] = 1;
    $_SESSION['username'] = $data['username'];
    $_SESSION['id_user'] = $data['id_user']; 
    $_SESSION['role'] = $data['role']; 
    
    header('location:index.php');

} else { 
    echo "<script>alert('Login Gagal, Silahkan Coba Lagi')</script>";
    echo "<meta http-equiv='refresh' content='1;url=login.php'>";
}

mysqli_close($conn); 
?>