<?php
include 'config.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Favorileri getir
$sql = "SELECT s.* FROM shows s 
        JOIN favorites f ON s.id = f.show_id 
        WHERE f.user_id = ? 
        ORDER BY f.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$favorites = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorilerim</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1 class="logo">🎬 Dizi-Film Blog</h1>
            <ul class="nav-links">
                <li><a href="index.php">Ana Sayfa</a></li>
                <li><a href="my_reviews.php">Yorumlarım</a></li>
                <li><a href="favorites.php" style="color: #ffeb3b;">⭐ Favorilerim</a></li>
                <?php if (is_admin()): ?>
                    <li><a href="admin.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Çıkış</a></li>
                <li><button class="dark-mode-toggle" onclick="toggleDarkMode()" title="Koyu Mod">🌙</button></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>⭐ Favorilerim</h2>
        
        <?php if (count($favorites) > 0): ?>
            <div class="shows-grid">
                <?php foreach ($favorites as $show): ?>
                    <div class="show-card">
                        <div class="show-image">
                            <img src="<?php echo $show['image_url'] ?: 'https://via.placeholder.com/300x400?text=Resim+Yok'; ?>" alt="<?php echo htmlspecialchars($show['title']); ?>">
                        </div>
                        <div class="show-info">
                            <h3><?php echo htmlspecialchars($show['title']); ?></h3>
                            <p class="genre"><?php echo htmlspecialchars($show['genre']); ?></p>
                            <p class="description"><?php echo substr(htmlspecialchars($show['description']), 0, 80) . '...'; ?></p>
                            <a href="detail.php?id=<?php echo $show['id']; ?>" class="btn">Detay</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="text-align:center; color:#999; padding:40px;">Henüz favoriye eklediğiniz dizi/film yok.</p>
        <?php endif; ?>
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
