<?php
include 'config.php';

if (!is_logged_in() || !is_admin()) {
    redirect('login.php');
}

$id = intval($_GET['id'] ?? 0);
$show = $conn->query("SELECT * FROM shows WHERE id = $id AND admin_id = {$_SESSION['user_id']}")->fetch_assoc();

if (!$show) {
    redirect('admin.php');
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $genre = trim($_POST['genre']);
    $image_url = trim($_POST['image_url']);
    
    if (!empty($title) && !empty($description)) {
        $sql = "UPDATE shows SET title=?, description=?, genre=?, image_url=? WHERE id=? AND admin_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $title, $description, $genre, $image_url, $id, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $message = 'Dizi/Film başarıyla güncellendi!';
            $show = array(
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'genre' => $genre,
                'image_url' => $image_url
            );
        }
    } else {
        $message = 'Lütfen başlık ve açıklamayı doldurun!';
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dizi/Film Düzenle</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1 class="logo">🎬 Dizi/Film Düzenle</h1>
            <ul class="nav-links">
                <li><a href="admin.php">Admin Paneli</a></li>
                <li><a href="index.php">Ana Sayfa</a></li>
                <li><a href="logout.php">Çıkış</a></li>
                <li><button class="dark-mode-toggle" onclick="toggleDarkMode()" title="Koyu Mod">🌙</button></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <?php if ($message): ?>
            <p style="background:#dfd; color:#2a2; padding:15px; border-radius:8px; margin-bottom:20px;">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <div style="background: var(--bg-secondary); padding: 30px; border-radius: 12px; box-shadow: var(--shadow); max-width: 600px;">
            <h2>Dizi/Film Düzenle</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Başlık *</label>
                    <input type="text" name="title" placeholder="Dizi/Film Adı" required value="<?php echo htmlspecialchars($show['title']); ?>">
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" name="genre" placeholder="Drama, Komedi, Aksiyon, vb." value="<?php echo htmlspecialchars($show['genre']); ?>">
                </div>

                <div class="form-group">
                    <label>Açıklama *</label>
                    <textarea name="description" placeholder="Dizi/Film hakkında bilgi..." rows="6" required><?php echo htmlspecialchars($show['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Görsel URL</label>
                    <input type="url" name="image_url" placeholder="https://..." value="<?php echo htmlspecialchars($show['image_url']); ?>">
                </div>

                <button type="submit" class="btn">Güncelle</button>
                <a href="admin.php" style="display: inline-block; margin-left: 10px; padding: 10px 20px; background: #999; color: white; border-radius: 8px; text-decoration: none;">İptal</a>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Dizi-Film Blog</p>
    </footer>

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
