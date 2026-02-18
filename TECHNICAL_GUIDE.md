# Technical Guide - Umrah & Hajj ERP System

## 1. System Architecture

### Overview

This system is a comprehensive ERP for managing Umrah and Hajj operations, built on **Laravel 12** with a **Tailwind CSS** frontend (using Claymorphism design). It handles the entire lifecycle from Pilgrim Registration, Package Management, Booking (Transactions), Payments, to Manifest Generation.

### Data Flow Logic

1.  **Registration/Input**:
    - **Agents**: Register external agents who bring pilgrims.
    - **Pilgrims**: Input individual pilgrim data (Name, Passport, etc.). Data can be imported via CSV.
    - **Packages**: Create travel packages with pricing (Quad, Triple, Double) and Quota.
2.  **Booking (Transaction)**:
    - The core of the system. A transaction links a **User/Agent** to a **Package** and contains multiple **Pilgrims** (Family/Group Booking).
    - **Validation**: Checks Package Quota and calculates Total Amount based on Room Type.
    - **Data Integrity**: Uses `DB::transaction` to ensure Transaction and Pilgrim records are created atomically.
3.  **Finance**:
    - Tracks **Payments** against Transactions.
    - Updates Transaction Status: `Pending` -> `Down Payment` -> `Paid`.
4.  **Operations**:
    - **Manifest Export**: Generates CSV for airlines/visa providers based on Booked Pilgrims in a Package.
    - **Dashboard**: Real-time analytics (Revenue, Active Pilgrims) with caching for performance.

### Directory Structure Highlights

- `app/Http/Controllers`:
    - `TransactionController`: Handles complex booking logic (multi-step).
    - `PilgrimController`: Handles CRUD and CSV Import/Export.
    - `DashboardController`: Aggregates statistics with Caching.
- `app/Models`:
    - `Transaction`: Central model (Relationships: `pilgrims`, `package`, `agent`, `payments`).
    - `Pilgrim`: Belongs to a Transaction.
- `app/Observers`:
    - `TransactionObserver`: Invalidate Dashboard cache on data changes.
- `database/migrations`:
    - Contains schema definitions including performance indexes.

## 2. Deployment Guide

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL 8.0+

### Installation Steps

1.  **Clone Repository**:
    ```bash
    git clone <repo_url>
    cd FSI-Board
    ```
2.  **Install Dependencies**:
    ```bash
    composer install --optimize-autoloader --no-dev
    npm install
    ```
3.  **Environment Setup**:
    ```bash
    cp .env.production.example .env
    # Edit .env with production DB credentials
    php artisan key:generate
    ```
4.  **Datebase Setup**:
    ```bash
    php artisan migrate
    # Optional: php artisan db:seed --class=UserSeeder (for initial admin)
    ```
5.  **Build Assets**:
    ```bash
    npm run build
    ```
6.  **Optimization**:
    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```

### File Permissions (Linux/Ubuntu)

Secure the application by setting correct ownership and permissions:

```bash
# Set owner to web server user (usually www-data)
sudo chown -R www-data:www-data /var/www/html/FSI-Board

# Set permissions for files and folders
sudo find /var/www/html/FSI-Board -type f -exec chmod 644 {} \;
sudo find /var/www/html/FSI-Board -type d -exec chmod 755 {} \;

# Allow write access to storage and cache
sudo chmod -R 775 /var/www/html/FSI-Board/storage
sudo chmod -R 775 /var/www/html/FSI-Board/bootstrap/cache
```

## 3. Security & Maintenance

- **Logs**: Configured to rotate daily (`/storage/logs/laravel-YYYY-MM-DD.log`).
- **Debug Mode**: Ensure `APP_DEBUG=false` in production.
- **Backups**: Regularly backup the MySQL database and the `.env` file.
