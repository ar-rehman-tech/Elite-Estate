# Elite Estates — Luxury Real Estate Platform

## Obsidian Aurora Theme
Deep Navy · Emerald · Platinum

## Setup

### Requirements
- Apache/Nginx with PHP 8.0+
- MySQL/MariaDB
- Composer (optional)

### Installation
1. Copy the `luxury_estate` folder to your web server root (e.g. `htdocs/` or `www/`)
2. Import `database/schema.sql` into MySQL
3. Edit `includes/db.php` with your database credentials
4. Visit `http://localhost/luxury_estate/`

### Configuration
- `includes/config.php` — Auto-detects base URL (no manual editing needed)
- `includes/db.php` — Database credentials

## Pages
- `/index.php` — Homepage with hero, featured properties, search, services, testimonials
- `/pages/search.php` — Property search/browse with filters
- `/pages/property-details.php?id=N` — Property detail page with gallery, inquiry form, share
- `/pages/favorites.php` — Saved properties (session-based)
- `/login.php` — Sign in
- `/register.php` — Create account
- `/logout.php` — Secure logout
- `/admin/dashboard.php` — Admin panel (requires admin role)
- `/admin/properties.php` — Manage listings
- `/admin/inquiries.php` — View inquiries
- `/admin/users.php` — Manage users

## Fixes Applied
- ✅ Logout error fixed (proper session destruction + siteUrl redirect)
- ✅ Share button on property details now works (with clipboard fallback + toast notification)
- ✅ Footer social icons replaced with real SVG icons (LinkedIn, Instagram, Facebook, YouTube)
- ✅ Footer navigation links all use correct siteUrl() paths
- ✅ Complete new theme: Deep Navy/Emerald/Platinum (Obsidian Aurora)
- ✅ New fonts: Playfair Display + DM Sans + Cinzel
- ✅ All CSS variables updated across all files
- ✅ Testimonial, card, and search section CSS added
- ✅ Service section class names fixed
- ✅ Page transition background updated to new theme
