<img width="1919" height="977" alt="Screenshot 2025-11-17 232329" src="https://github.com/user-attachments/assets/c7ba8189-ff15-4955-95a4-300aebc295a9" />

# 🚀 ChirpBox — A Simple Twitter-Like Microblogging App
A lightweight Laravel project where users can register, log in, and post short messages (“chirps”).
Each user can **create, edit, and delete only their own chirps**, and view other users’ posts in a feed.

## 📌 Features

### 🔐 Authentication
- User Registration
- User Login / Logout
- Password-protected routes
- Auth middleware handling

### 📝 Chirp Functionality
- Create new chirps
- Edit existing chirps
- Delete chirps
- Users can only update/delete their own posts
- Display feed with all users' chirps

### 🎨 UI & Styling
- Built with **TailwindCSS**
- Clean and modern design

### ⚙️ Backend
- Laravel MVC architecture
- Eloquent ORM
- Form Request Validation
- Route protection
- Database migrations & seeders

## 🛠️ Tech Stack
| Technology | Usage |
|-----------|--------|
| Laravel 12+ | Backend framework |
| PHP 8+ | Core language |
| TailwindCSS | Styling |
| MySQL | Database |
| Blade | Templating engine |

## ⚙️ Installation & Setup
```bash
git clone https://github.com/HirenPatel555/chirpbox.git
cd chirpbox
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Update your .env, then:
```bash
php artisan migrate
php artisan serve
npm run dev
```

## 🤝 Contribution
Feel free to modify and learn from this project.
