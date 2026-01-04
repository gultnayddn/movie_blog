<?php
include 'config.php';

if (!is_logged_in() || !is_admin()) {
    redirect('login.php');
}

$message = '';
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM shows WHERE id = $id AND admin_id = {$_SESSION['user_id']}");
    $message = 'Silindi!';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $genre = trim($_POST['genre']);
    $image_url = trim($_POST['image_url']);
    
    if (!empty($title) && !empty($description)) {
        $sql = "INSERT INTO shows (title, description, genre, image_url, admin_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $title, $description, $genre, $image_url, $_SESSION['user_id']);
        $stmt->execute();
        $message = 'Eklendi!';
    }
}

$shows = $conn->query("SELECT * FROM shows ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1 class="logo">🎬 Admin Paneli</h1>
            <ul class="nav-links">
                <li><a href="index.php">Ana Sayfa</a></li>
                <li><a href="logout.php">Çıkış</a></li>
                <li><button class="dark-mode-toggle" onclick="toggleDarkMode()" title="Koyu Mod">🌙</button></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <?php if ($message): ?>
            <p style="color:green; background:#dfd; padding:10px; border-radius:5px;"><?php echo $message; ?></p>
        <?php endif; ?>

        <h2>Dizi/Film Yönetimi</h2>
        <a href="add_show.php" class="btn" style="margin-bottom: 20px; display: inline-block; width: auto;">➕ Yeni Dizi/Film Ekle</a>

        <h3>Tüm Diziler/Filmler</h3>
        <table style="width:100%; border-collapse:collapse;">
            <tr style="background:#667eea; color:white;">
                <th style="padding:10px; text-align:left;">Başlık</th>
                <th style="padding:10px; text-align:left;">Kategori</th>
                <th style="padding:10px; text-align:left;">İşlemler</th>
            </tr>
            <?php foreach ($shows as $show): ?>
                <tr style="border-bottom:1px solid #ddd;">
                    <td style="padding:10px;"><?php echo htmlspecialchars($show['title']); ?></td>
                    <td style="padding:10px;"><?php echo htmlspecialchars($show['genre']); ?></td>
                    <td style="padding:10px;">
                        <a href="edit_show.php?id=<?php echo $show['id']; ?>" style="display: inline-block; background: #667eea; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; margin-right: 5px;">✏️ Düzenle</a>
                        <a href="?delete=<?php echo $show['id']; ?>" onclick="return confirm('Emin misiniz?')" style="display: inline-block; background: #f56565; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none;">🗑️ Sil</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
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

    <footer>
        <p>&copy; 2026 Dizi-Film Blog</p>
    </footer>
</body>
</html>
