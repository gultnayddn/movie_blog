<?php
include 'config.php';

// Arama ve filtreleme
$search = trim($_GET['search'] ?? '');
$sql = "SELECT * FROM shows ORDER BY created_at DESC";

if (!empty($search)) {
    $search_term = '%' . $search . '%';
    $sql = "SELECT * FROM shows WHERE title LIKE ? OR description LIKE ? OR genre LIKE ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $search_term, $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

$shows = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dizi-Film Blog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1 class="logo">🎬 Dizi-Film Blog</h1>
            <ul class="nav-links">
                <li><a href="index.php">Ana Sayfa</a></li>
                <?php if (is_logged_in()): ?>
                    <li><a href="my_reviews.php">Yorumlarım</a></li>
                    <li><a href="favorites.php">⭐ Favorilerim</a></li>
                    <?php if (is_admin()): ?>
                        <li><a href="admin.php">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Çıkış (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php">Giriş</a></li>
                    <li><a href="register.php">Kayıt</a></li>
                <?php endif; ?>
                <li><button class="dark-mode-toggle" onclick="toggleDarkMode()" title="Koyu Mod">🌙</button></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>Popüler Diziler ve Filmler</h2>
        
        <!-- Arama Formu -->
        <div style="margin: 20px 0; background: white; padding: 15px; border-radius: 10px;">
            <form method="GET" style="display: flex; gap: 10px;">
                <input type="text" name="search" placeholder="Dizi/Film ara..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <button type="submit" class="btn" style="padding: 10px 20px;">Ara</button>
                <a href="index.php" class="btn" style="padding: 10px 20px; background: #999;">Temizle</a>
            </form>
        </div>
        
        <?php if (count($shows) > 0): ?>
            <div class="shows-grid">
                <?php foreach ($shows as $show): ?>
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
            <p style="text-align:center; color:#999;">Henüz dizi/film eklenmemiş.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2026 Dizi-Film Blog</p>
    </footer>

    <script>
        // Koyu mod toggle
        function toggleDarkMode() {
            const body = document.body;
            body.classList.toggle('dark-mode');
            
            // localStorage'a kaydet
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
                updateToggleButton(true);
            } else {
                localStorage.setItem('darkMode', 'disabled');
                updateToggleButton(false);
            }
        }

        // Sayfa yüklenmesinde koyu modu kontrol et
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

        // Toggle butonunun metnini güncelle
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

