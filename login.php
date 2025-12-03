<!DOCTYPE html>
<html>
<head>
    <title>Login Member Gym</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <section class="login-box">
        <h2>Login Manajemen Gym</h2>
        <form method="post" action="ceklogin.php">
            <input type="text" placeholder="username" name="username" id="username" required>
            <input type="password" placeholder="password" name="password" id="password" required>
            <input type="submit" value="Login">
        </form>
        <div style="text-align: center; margin-top: 10px;">
            <p>Belum punya akun? <a href="buat_akun.php" style="color: #6495ED;">Daftar di sini</a></p>
        </div>
    </section>
</div>
</body>
</html>