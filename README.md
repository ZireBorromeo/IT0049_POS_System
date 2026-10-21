# IT0049 Midterm Project — Complete Point-of-Sale System

A beginner-friendly POS system built with CodeIgniter 4, PHP, MySQL/MariaDB, and a simple responsive interface. It follows the IT0049 assignment's required tables and features: product, customer, and staff CRUD; staff login; image/avatar uploads; sale recording with stock deduction; and sales history.

> This package contains project source code and setup instructions. A live deployment, GitHub repository, and end-to-end runtime test have **not** been performed by packaging this ZIP.

## Requirements

- Windows with XAMPP
- PHP 8.1 or newer (PHP 8.2+ recommended)
- MySQL/MariaDB enabled in XAMPP
- Composer
- PHP extensions required by CodeIgniter 4, including `intl` and `mysqli`

## 1. Place the project

1. Extract this ZIP into `C:\xampp\htdocs\it0049_pos`.
2. Open XAMPP Control Panel and start **Apache** and **MySQL**.
3. Open a terminal in `C:\xampp\htdocs\it0049_pos`.

## 2. Install CodeIgniter

Run:

```bash
composer install
```

If Composer reports that `ext-intl` is missing, open `C:\xampp\php\php.ini`, find `;extension=intl`, remove the leading semicolon, save, and restart Apache. Check that `extension=mysqli` is also enabled. Restart the terminal after editing PHP configuration.

## 3. Create the database

1. Open `http://localhost/phpmyadmin`.
2. Choose **Import** and import `database/it0049_pos.sql`.
3. The SQL creates the `it0049_pos` database, the four core entity tables plus sales (five tables total), and a few sample products/customers.
4. The defaults in `app/Config/Database.php` are set for a typical local XAMPP installation (MySQL user `root`, blank password). If your credentials differ, edit that config file before running the app. Update `app/Config/App.php` if you use a different base URL.

The SQL file drops and recreates these project tables. Do not import it into a database that contains data you need to keep.

## 4. Create the initial staff account

In the project directory, run:

```bash
php spark db:seed InitialUserSeeder
```

Initial login:
- **Username:** `admin`
- **Password:** `Admin123!`

Change the initial password by editing the admin account in **Staff** after logging in. This is a development/demo credential; do not use it for a public production deployment.

## 5. Start the app

Recommended development command:

```bash
php spark serve
```

Open `http://localhost:8080`.

If you want to use Apache through XAMPP instead, point the site's document root to the project's `public` directory. Do not expose the project root or the `writable` directory as the public web root.

## Features

- Login/logout and an authentication filter protecting all management routes.
- Product CRUD with validated image uploads (JPG, PNG, WebP; maximum 2 MB).
- Customer CRUD.
- Staff CRUD with avatar uploads and `password_hash()` password storage.
- Record sale with optional customer, calculated total, locked stock check, and transaction-safe stock deduction.
- Sales history with product, customer, staff member, quantity, total, and timestamp.
- Relational foreign keys between sales, products, customers, and staff.
- Responsive dashboard and tables.

## Important implementation notes

- Sale recording is performed in a database transaction. The product row is locked while stock is checked and updated, reducing the chance of overselling when two sales are submitted at once.
- A product or staff account with existing sales history cannot be deleted because its history uses a foreign key. A customer referenced by a sale is also protected from deletion by the application; a walk-in sale has no customer ID.
- The app uses CodeIgniter validation and CSRF fields on forms. Uploaded filenames are randomized and image types are checked.
- The assignment's schema stores one product per sale record. This implementation follows that schema; a multi-item cart would require a separate sale header/details design and is not included.
- Currency display uses Philippine pesos (₱).

## Quick manual test checklist

1. Log in with the initial account.
2. Add a product with price and stock; upload a JPG/PNG/WebP image.
3. Edit the product and verify the listing reflects the changes.
4. Add, edit, and delete a customer with no sale history.
5. Create a second staff account and verify its password is not stored as plain text in the database.
6. Log out and confirm that `/products` redirects to `/login`.
7. Record a sale for a product with available stock. Confirm the stock decreases by the quantity and the sale appears in Sales History.
8. Try selling more than the available stock. Confirm the sale is rejected and inventory is unchanged.
9. Check the sales history displays the product, optional customer, staff, quantity, total, and date.
10. Try deleting a product/staff member with sales history and confirm the app blocks the deletion.

## GitHub submission

After local testing, create a GitHub repository and push the project source. Do not commit `.env`, `vendor/`, credentials, or real customer data. Include this README and the SQL export.

## Hosting

Deploy only after local tests pass. Use a PHP/MySQL host compatible with CodeIgniter 4, configure the database credentials through the host's environment configuration, set the correct `app.baseURL`, and make the `public` directory the web root. Do not publish the demo password or expose writable/private project files.

## Troubleshooting

- **Missing `vendor/codeigniter4/framework/system/Boot.php`:** Run `composer install` in the project root.
- **`ext-intl` missing:** Enable `extension=intl` in the PHP configuration used by Composer, then reopen the terminal.
- **Database connection error:** Confirm MySQL is running, the `it0049_pos` database exists, and credentials in `app/Config/Database.php` are correct.
- **404 for CSS/images:** Ensure `app.baseURL` matches the URL you use and that `public` is the web root.
- **Uploads fail:** Ensure `public/uploads/products` and `public/uploads/avatars` exist and are writable by PHP.

## Project structure

```text
app/
  Config/       App, Database, Filters, Paths, Routes
  Controllers/  Auth, Dashboard, Products, Customers, Staff, Sales
  Database/Seeds/InitialUserSeeder.php
  Filters/      AuthFilter.php
  Models/       ProductModel, CustomerModel, UserModel, SaleModel
  Views/        auth, dashboard, products, customers, staff, sales, layout
database/
  it0049_pos.sql
public/
  assets/style.css
  uploads/
writable/
composer.json
.env.example
README.md
```
