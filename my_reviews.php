<?php
include 'config.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$reviews = $conn->query("SELECT r.*, s.title FROM reviews r JOIN shows s ON r.show_id = s.id WHERE r.user_id = {$_SESSION['user_id']} ORDER BY r.created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yorumlarım</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1 class="logo">🎬 Dizi-Film Blog</h1>
            <ul class="nav-links">
                <li><a href="index.php">Ana Sayfa</a></li>
                <li><a href="my_reviews.php">Yorumlarım</a></li>
                <li><a href="favorites.php">⭐ Favorilerim</a></li>
                <li><a href="logout.php">Çıkış</a></li>
                <li><button class="dark-mode-toggle" onclick="toggleDarkMode()" title="Koyu Mod">🌙</button></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>Yorumlarım</h2>
        
        <?php if (count($reviews) > 0): ?>
            <table style="width:100%; border-collapse:collapse;">
                <tr style="background:#667eea; color:white;">
                    <th style="padding:10px; text-align:left;">Dizi/Film</th>
                    <th style="padding:10px; text-align:left;">Puan</th>
                    <th style="padding:10px; text-align:left;">Yorum</th>
                    <th style="padding:10px; text-align:left;">Tarih</th>
                </tr>
                <?php foreach ($reviews as $r): ?>
                    <tr style="border-bottom:1px solid #ddd;">
                        <td style="padding:10px;"><a href="detail.php?id=<?php echo $r['show_id']; ?>"><?php echo htmlspecialchars($r['title']); ?></a></td>
                        <td style="padding:10px;">⭐ <?php echo $r['rating']; ?>/10</td>
                        <td style="padding:10px;"><?php echo substr(htmlspecialchars($r['comment']), 0, 50) . '...'; ?></td>
                        <td style="padding:10px;"><?php echo date('d.m.Y', strtotime($r['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="text-align:center; color:#999;">Henüz yorum yapmadınız.</p>
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
