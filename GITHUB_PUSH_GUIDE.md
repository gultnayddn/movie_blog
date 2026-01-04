# GitHub'a Push Etme Adımları

## 1️⃣ GitHub'da Repository Oluştur

1. GitHub'a giriş yap: https://github.com
2. Sağ üst köşede **"+"** butonuna tıkla
3. **"New repository"** seç
4. Repository adı: `dizi-blog`
5. Description: `3-role blog system with CRUD operations using PHP & MySQL`
6. Visibility: **Public** (GitHub Portfolio için)
7. **"Create repository"** butonuna tıkla

---

## 2️⃣ Remote Add Et

Aşağıdaki komutu çalıştır (USERNAME'ini değiştir):

```bash
git remote add origin https://github.com/USERNAME/dizi-blog.git
git branch -M main
git push -u origin main
```

---

## 3️⃣ Push Et

```bash
git push
```

---

## 4️⃣ Sonuç

✅ Tebrikler! Projen GitHub'da yayında!
- GitHub URL: https://github.com/USERNAME/dizi-blog
- README: Otomatik olarak gösterilecek

---

## 📝 Commit Mesajları Örnekleri

Gelecekteki güncellemeler için:

```bash
# Yeni feature eklemek
git add .
git commit -m "Add email verification feature"
git push

# Bug fix
git commit -m "Fix: comment submit error on detail page"
git push

# Documentation
git commit -m "docs: update README with deployment guide"
git push
```

---

## 🌟 GitHub'da Güzel Gözükmesi için

1. **README.md** ✅ (zaten var)
2. **Screenshot'lar** - README'de göster
3. **.gitignore** ✅ (zaten var)
4. **Açık Lisans** - LICENSE dosyası ekle
5. **Topics** - GitHub repo ayarlarında "php", "mysql", "web-development" ekle

---

**Hazırlarsan Medium blog yazısını yapabiliriz!**
