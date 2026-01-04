<?php
include 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);
    
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Tüm alanları doldurun!';
    } elseif ($password !== $confirm) {
        $error = 'Şifreler eşleşmiyor!';
    } else {
        $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = 'Kullanıcı adı veya email zaten kayıtlı!';
        } else {
            $hashed = md5($password);
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $hashed);
            if ($stmt->execute()) {
                echo "<script>alert('Kayıt başarılı! Giriş yapabilirsiniz.'); window.location='login.php';</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-page">
        <button class="dark-mode-toggle" onclick="toggleDarkMode()" style="position: fixed; top: 20px; right: 20px; z-index: 200;" title="Koyu Mod">🌙</button>
        <div class="auth-box">
            <h2>Kayıt Ol</h2>
            <?php if ($error): ?>
                <p style="color:red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Kullanıcı Adı" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Şifre" required>
                <input type="password" name="confirm" placeholder="Şifre Onayla" required>
                <button type="submit">Kayıt Ol</button>
            </form>
            <p>Zaten hesabınız var mı? <a href="login.php">Giriş yapın</a></p>
        </div>
    </div>

    <script>
        function toggleDarkMode() {
            const body = document.body;
            body.classList.toggle('dark-mode');
            
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
                updateToggleButton(true);
            } else {
                localStorage.setItem('darkMode', 'disabled');
                updateToggleButton(false);
            }
        }

        window.addEventListener('DOMContentLoaded', function() {
            const darkMode = localStorage.getItem('darkMode');
            const button = document.querySelector('.dark-mode-toggle');
            
            if (darkMode === 'enabled') {
                document.body.classList.add('dark-mode');
                updateToggleButton(true);
            } else {
                updateToggleButton(false);
            }
        });

        function updateToggleButton(isDarkMode) {
            const button = document.querySelector('.dark-mode-toggle');
            if (button) {
                button.textContent = isDarkMode ? '☀️' : '🌙';
                button.title = isDarkMode ? 'Açık Mod' : 'Koyu Mod';
            }
        }
    </script>
</body>
</html>
