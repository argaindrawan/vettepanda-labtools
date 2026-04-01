# Research Inventory Management (XAMPP)

Simple web-based inventory system for research equipment and materials using PHP, JavaScript, MySQL.

## Setup
1. Copy the folder contents into `C:\xampp\htdocs\research-inventory`.
2. Start Apache + MySQL in XAMPP.
3. Open phpMyAdmin (http://localhost/phpmyadmin).
4. Create database `research_inventory` and import `db.sql`.
5. Configure DB credentials in `api.php` if needed.
6. Visit `http://localhost/research-inventory/index.php`.

## Features
- Add equipment/materials
- Edit records
- Delete records
- Search by name/type
- Filter by category
- Persistent storage in MySQL
