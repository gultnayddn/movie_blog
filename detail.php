<?php
include 'config.php';

$id = intval($_GET['id'] ?? 0);
$show = $conn->query("SELECT * FROM shows WHERE id = $id")->fetch_assoc();

if (!$show) {
    redirect('index.php');
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_logged_in()) {
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment'] ?? '');
    
    if ($rating >= 1 && $rating <= 10 && !empty($comment)) {
        $sql = "INSERT INTO reviews (show_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $id, $_SESSION['user_id'], $rating, $comment);
        if ($stmt->execute()) {
            $message = 'Yorumunuz eklendi!';
        } else {
            $message = 'Hata: ' . $stmt->error;
        }
    } else {
        $message = 'Lütfen puan seçin ve yorum yazın!';
    }
}

$reviews = $conn->query("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.show_id = $id ORDER BY r.created_at DESC")->fetch_all(MYSQLI_ASSOC);
$avg = count($reviews) > 0 ? round(array_sum(array_column($reviews, 'rating')) / count($reviews), 1) : 0;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($show['title']); ?></title>
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
        <div style="background:white; padding:20px; border-radius:10px; margin-bottom:30px; display:grid; grid-template-columns:250px 1fr; gap:30px;">
            <img src="<?php echo $show['image_url'] ?: 'https://via.placeholder.com/250x350?text=Resim+Yok'; ?>" alt="<?php echo htmlspecialchars($show['title']); ?>" style="width:100%; border-radius:10px;">
            <div>
                <h1><?php echo htmlspecialchars($show['title']); ?></h1>
                <p><strong>Kategori:</strong> <?php echo htmlspecialchars($show['genre']); ?></p>
                <p><strong>Ortalama Puan:</strong> ⭐ <?php echo $avg; ?> (<?php echo count($reviews); ?> yorum)</p>
                <?php if (is_logged_in()): ?>
                    <button id="favorite-btn" class="btn" style="background: #ffeb3b; color: #333; margin-bottom: 15px;" onclick="toggleFavorite(<?php echo $show['id']; ?>)">
                        <span id="favorite-text">⭐ Favorilere Ekle</span>
                    </button>
                <?php endif; ?>
                <p><?php echo nl2br(htmlspecialchars($show['description'])); ?></p>
            </div>
        </div>

        <h2>Yorumlar</h2>

        <?php if ($message): ?>
            <p style="color:green; background:#dfd; padding:10px; border-radius:5px;"><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if (is_logged_in()): ?>
            <form method="POST" style="background:#f9f9f9; padding:20px; border-radius:10px; margin-bottom:20px;">
                <div>
                    <label>Puan Seçin (1-10):</label>
                    <select name="rating" required style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #ddd; border-radius:5px;">
                        <option value="">Seçin...</option>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?>/10</option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label>Yorumunuz:</label>
                    <textarea name="comment" placeholder="Yorumunuz..." rows="4" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px; margin-bottom:10px;"></textarea>
                </div>
                <button type="submit" class="btn" style="width:100%; padding:12px; background:#667eea; color:white; border:none; border-radius:5px; cursor:pointer;">Gönder</button>
            </form>
        <?php else: ?>
            <p><a href="login.php">Yorum yapabilmek için giriş yapınız.</a></p>
        <?php endif; ?>

        <?php foreach ($reviews as $review): ?>
            <div style="background:white; padding:15px; border-radius:10px; margin-bottom:10px; border-left:4px solid #667eea;">
                <strong><?php echo htmlspecialchars($review['username']); ?></strong> - ⭐ <?php echo $review['rating']; ?>/10
                <p><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                <small style="color:#999;"><?php echo date('d.m.Y H:i', strtotime($review['created_at'])); ?></small>
            </div>
        <?php endforeach; ?>

        <?php if (count($reviews) === 0): ?>
            <p style="text-align:center; color:#999;">Henüz yorum yok.</p>
        <?php endif; ?>
    </div>

    <script>
    function toggleFavorite(showId) {
        const btn = document.getElementById('favorite-btn');
        const txt = document.getElementById('favorite-text');
        
        // Favoriye ekleme/çıkarma durumunu kontrol et
        fetch('api_favorite.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=check&show_id=' + showId
        })
        .then(r => r.json())
        .then(data => {
            if (data.is_favorite) {
                // Favorilerden çıkar
                fetch('api_favorite.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'action=remove&show_id=' + showId
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        txt.textContent = '⭐ Favorilere Ekle';
                        btn.style.background = '#ffeb3b';
                    }
                });
            } else {
                // Favorilere ekle
                fetch('api_favorite.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'action=add&show_id=' + showId
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        txt.textContent = '✓ Favorilerde';
                        btn.style.background = '#4caf50';
                    }
                });
            }
        });
    }

    // Sayfa yüklendiğinde favori durumunu kontrol et
    window.addEventListener('load', function() {
        const showId = <?php echo $show['id']; ?>;
        if (<?php echo is_logged_in() ? 'true' : 'false'; ?>) {
            fetch('api_favorite.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=check&show_id=' + showId
            })
            .then(r => r.json())
            .then(data => {
                if (data.is_favorite) {
                    document.getElementById('favorite-text').textContent = '✓ Favorilerde';
                    document.getElementById('favorite-btn').style.background = '#4caf50';
                }
            });
        }
    });

    // Koyu mod toggle
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

