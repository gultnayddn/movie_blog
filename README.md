# 🎬 Dizi-Film Blog - 3 Rollü Dinamik Web Sitesi

## 📋 Proje Özeti

**Dizi-Film Blog**, PHP ve MySQL kullanarak geliştirilmiş, 3 farklı kullanıcı rolüne sahip tam işlevsel bir web uygulamasıdır. Proje, giriş sistemi, veritabanı yönetimi, CRUD işlemleri ve modern UI/UX tasarımı için önemli konseptleri içermektedir.

## ✨ Özellikler

### 👨‍💼 Admin Rolü
- ✅ Dizi/Film Ekleme (CREATE)
- ✅ Dizi/Film Düzenleme (UPDATE)
- ✅ Dizi/Film Silme (DELETE)
- ✅ Tüm Dizi/Film Listesi Görüntüleme
- ✅ Admin Paneli Erişimi

### 👤 Kullanıcı Rolü
- ✅ Yorum/Inceleme Yazma (CREATE)
- ✅ Kendi Yorumlarını Görüntüleme (READ)
- ✅ Favorilere Dizi/Film Ekleme
- ✅ Favori Listesi Yönetimi
- ✅ Profil Sayfası

### 👥 Misafir Rolü
- ✅ Dizi/Film Listesini Görüntüleme (READ)
- ✅ Dizi Detaylarını Görüntüleme
- ✅ Yorumları Okuma
- ❌ Yorum Yazamaz
- ❌ Favori Ekleyemez

## 🛠️ Teknik Stack

| Kategori | Teknoloji |
|----------|-----------|
| **Backend** | PHP 8.0.30 |
| **Database** | MySQL (İlişkisel) |
| **Frontend** | HTML5, CSS3, JavaScript (Vanilla) |
| **Server** | Apache 2.4.58 (XAMPP) |
| **Authentication** | Session-based |
| **API** | AJAX (Fetch API) |

## 📊 Veritabanı Tasarımı

### ER Diagram

```
┌──────────────────┐
│     USERS        │
├──────────────────┤
│ id (PK)          │
│ username (UQ)    │
│ email (UQ)       │
│ password         │
│ is_admin         │
│ created_at       │
└──────────────────┘
         │
         ├─────────┬──────────────┬──────────────┐
         │         │              │              │
         ▼         ▼              ▼              ▼
    ┌────────┐ ┌──────────┐ ┌───────────┐ ┌───────────┐
    │ SHOWS  │ │ REVIEWS  │ │FAVORITES  │ │ REVIEWS   │
    └────────┘ └──────────┘ └───────────┘ └───────────┘
    (admin_id) (user_id)    (user_id)    (user_id)
              (show_id)     (show_id)    (show_id)
```

### Tablolar

#### Users Tablosu
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Shows Tablosu
```sql
CREATE TABLE shows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    genre VARCHAR(100),
    image_url VARCHAR(500),
    admin_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id)
);
```

#### Reviews Tablosu
```sql
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    show_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 10),
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (show_id) REFERENCES shows(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

#### Favorites Tablosu
```sql
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    show_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_favorite (user_id, show_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (show_id) REFERENCES shows(id)
);
```

## 🚀 Kurulum Talimatları

### Gereksinimler
- XAMPP (Apache + MySQL + PHP)
- Web Tarayıcı (Chrome, Firefox, vb.)

### Adımlar

1. **XAMPP İndir ve Kur**
   ```bash
   # İndir: https://www.apachefriends.org/
   # Kur ve start et
   ```

2. **Projeyi Kopyala**
   ```bash
   cd C:\app\xampp\htdocs
   git clone https://github.com/USERNAME/dizi-blog.git
   cd dizi-blog
   ```

3. **Veritabanı Oluştur**
   - phpMyAdmin'e gir: http://localhost/phpmyadmin/
   - Yeni database oluştur: `dizi_blog`
   - `database.sql` ve `add_favorites.sql` dosyalarını import et

4. **Siteye Erişim**
   ```
   http://localhost/dizi_blog/
   ```

## 🔐 Demo Hesapları

| Rol | Kullanıcı Adı | Şifre | Açıklama |
|-----|---|---|---|
| Admin | admin | admin | Tüm yetkilere sahip |
| User | user1 | 1 | Normal kullanıcı |
| Guest | - | - | Giriş yapmadan göz at |

## 📁 Dosya Yapısı

```
dizi-blog/
├── index.php           # Ana sayfa - dizi listesi
├── login.php           # Giriş sayfası
├── register.php        # Kayıt sayfası
├── logout.php          # Çıkış
├── detail.php          # Dizi detay sayfası
├── admin.php           # Admin paneli
├── add_show.php        # Dizi ekleme
├── edit_show.php       # Dizi düzenleme
├── my_reviews.php      # Kullanıcının yorumları
├── favorites.php       # Favoriler
├── api_favorite.php    # AJAX endpoint
├── config.php          # Veritabanı config + helper functions
├── css/
│   └── style.css       # Tüm styling (dark mode dahil)
├── database.sql        # Veritabanı schema
├── add_favorites.sql   # Favorites table
└── README.md           # Bu dosya
```

## 🎨 Özellikler

### Dark Mode
- 🌙 Toggle buton navbar'da
- 💾 localStorage'a kaydedilir
- 🎯 Tüm sayfalarda çalışır
- ⚡ Smooth transition animasyonları

### Responsif Tasarım
- 📱 Mobil cihazlara uyumlu
- 💻 Masaüstü optimize
- 🖥️ Tablet desteği

### Güvenlik
- 🔒 Prepared Statements (SQL Injection koruması)
- 🔐 Password Hashing (MD5)
- 🛡️ Session-based Authentication
- ✅ Input Validation

## 💡 Kullanılan Konseptler

### Backend
- [x] Database Connection & Configuration
- [x] Session Management
- [x] User Authentication
- [x] Role-based Access Control
- [x] CRUD Operations
- [x] Prepared Statements
- [x] Form Validation

### Frontend
- [x] Responsive Grid Layout
- [x] Form Handling
- [x] AJAX (Fetch API)
- [x] CSS Custom Properties
- [x] Dark Mode Toggle
- [x] DOM Manipulation

### Database
- [x] Relational Database Design
- [x] Foreign Keys
- [x] Unique Constraints
- [x] Timestamps
- [x] SQL Queries (SELECT, INSERT, UPDATE, DELETE, JOIN)

## 🔄 CRUD İşlemleri

### Admin CRUD (Shows)
- **Create**: `add_show.php` → INSERT into shows
- **Read**: `admin.php` → SELECT from shows
- **Update**: `edit_show.php` → UPDATE shows
- **Delete**: `admin.php` → DELETE from shows

### User CRUD (Reviews)
- **Create**: `detail.php` → INSERT into reviews
- **Read**: `my_reviews.php`, `detail.php` → SELECT from reviews
- **Update**: ❌ (Reviewlar edit edilemiyor)
- **Delete**: ❌ (Reviewlar silinemez)

### User CRUD (Favorites)
- **Create**: `api_favorite.php` → INSERT into favorites
- **Read**: `favorites.php` → SELECT from favorites
- **Update**: ❌
- **Delete**: `api_favorite.php` → DELETE from favorites

## 📈 Proje İstatistikleri

| Metrik | Değer |
|--------|-------|
| Dosya Sayısı | 14+ |
| PHP Dosyası | 10 |
| Veritabanı Tablosu | 4 |
| Kullanıcı Rolü | 3 |
| CRUD İşlemi | 13 |

## 🚀 Geliştirme Fikirleri

- [ ] Email doğrulama
- [ ] Şifre sıfırlama
- [ ] Yönetici panelinde istatistikler
- [ ] Pagination
- [ ] Rating yıldız sistemi
- [ ] Kullanıcı profil resmi
- [ ] Bildirim sistemi
- [ ] API (REST)

## 📝 Lisans

Bu proje eğitim amaçlı oluşturulmuştur. Özgürce kullanabilir ve değiştirebilirsiniz.

## 👨‍💻 Geliştirici

**Dizi-Film Blog** | PHP + MySQL Örnek Projesi

---

**Hazırlanan**: 2026 Ocak
**Versiyon**: 1.0
