# Laravel 12 + Breeze (Blade) + Filament Starter

A reusable Laravel 12 starter project with Breeze authentication (Blade stack) and Filament admin panel for managing users.  
Configured for admin-only access to the Filament panel, with a seeded admin user.

---

## Features

- **Laravel 12** framework
- **Breeze** (Blade) auth scaffolding
- **Filament v3** admin panel
  - Users table: name, email, admin toggle, email verified, created date
  - Filters: admin/non-admin, verified/unverified, created date range
  - Searchable by name/email
  - Create/Edit/Delete users from the panel
- **Admin-only login** via `/admin/login`
- **Seeded admin user** with credentials from `.env`
- **Committed built assets** in `/public/build` for devs without Node
- **Windows XAMPP** setup guide with hosts & Apache vhost
- PHP extension requirements documented

---

## Requirements

- PHP 8.2+ (8.3 recommended)
- Composer 2.x
- MySQL/MariaDB (or SQLite for quick start)
- Node.js 20+ (only for developers editing CSS/JS)

---

## Getting Started

### 1. Clone the repo
```bash
git clone https://github.com/abmegypt/laravel12-breeze-filament-starter.git
cd laravel12-breeze-filament-starter
```

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Environment setup
```bash
cp .env.example .env
php artisan key:generate
```
Edit `.env`:
```
APP_NAME="ProjectName"
APP_URL=http://project.local

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_name
DB_USERNAME=root
DB_PASSWORD=

ADMIN_EMAIL=admin@project.local
ADMIN_PASSWORD=ChangeMeNow!
```

Create the database in MySQL before proceeding.

### 4. Build assets (if you’re editing CSS/JS)
```bash
npm ci
npm run build
```
If you’re not editing assets, skip this step — built assets are already committed.

### 5. Migrate & seed
```bash
php artisan migrate --seed
```

### 6. Hosts & Apache vhost (Windows XAMPP)
Add to `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1 project.local
```

Edit `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:
```apache
<VirtualHost *:80>
    ServerName project.local
    DocumentRoot "C:/xampp/htdocs/laravel12-breeze-filament-starter/public"

    <Directory "C:/xampp/htdocs/laravel12-breeze-filament-starter/public">
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "logs/project-error.log"
    CustomLog "logs/project-access.log" common
</VirtualHost>
```
Restart Apache.

---

## Usage

### Admin Panel
- URL: `http://project.local/admin/login`
- Email: `admin@project.local`  
- Password: `ChangeMeNow!` (or from `.env`)

Only users with `is_admin = 1` can log in.

### Breeze Auth
The default Laravel `/login` route is available for non-admin authentication.

---

## PHP Extensions

Ensure the following extensions are enabled in `php.ini` (both local and server):
- `bcmath`
- `ctype`
- `fileinfo`
- `json`
- `mbstring`
- `openssl`
- `pdo_mysql` (or `pdo_sqlite`)
- `tokenizer`
- `xml`
- `curl`
- **Recommended**: `intl`, `gd` or `imagick`, `zip`, `sodium`, `exif`

For XAMPP on Windows:  
Remove the leading `;` from the extension line in `php.ini` and restart Apache.

---

## Windows Defender Performance Tip

Exclude:
- **Project folder**: `C:\xampp\htdocs\laravel12-breeze-filament-starter`
- **Composer cache**: `%LOCALAPPDATA%\Composer` and `%APPDATA%\Composer`
- **Optional**: npm cache `%APPDATA%\npm-cache`

---

## Git Workflow

- `main` branch is protected
- Create feature branches: `feat/...` or `fix/...`
- Submit pull requests for review
- Keep commits atomic and descriptive

---

## License
This project is open-sourced under the [MIT license](LICENSE).

