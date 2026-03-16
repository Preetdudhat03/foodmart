# 🛒 FoodMart - E-Commerce Grocery Store

FoodMart is a modern, responsive e-commerce platform designed for grocery shopping. Built with **PHP**, **MySQL**, and **Bootstrap**, it offers a seamless shopping experience with features like product filtering, cart management, and order tracking.

---

## ✨ Features

- **User Authentication**: Secure Login & Registration system.
- **Product Catalog**: Categorized products (Fruits, Dairy, Juices, etc.) with search functionality.
- **Shopping Cart**: Real-time cart management to add/update items.
- **Wishlist**: Save favorite items for later.
- **Order Management**: Checkout flow and order history tracking.
- **Subscription**: Newsletter subscription for users.
- **Responsive Design**: Fully optimized for mobile, tablet, and desktop views.

---

## 🛠️ Technical Stack

- **Frontend**: Bootstrap 5, jQuery, HTML5, CSS3.
- **Backend**: PHP (Vanilla).
- **Database**: MySQL.
- **Libraries**: PHPMailer, Swiper Slider, Chocolat.js, Magnific Popup.

---

## 🚀 Installation Guide (XAMPP)

Follow these steps to get the project running locally on your machine using XAMPP.

### 1. Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) installed on your system.

### 2. Download Project
- Download or clone this repository.
- Extract the folder and rename it to `FoodMart` (optional, but recommended).

### 3. Move to Web Directory
Copy the `FoodMart` folder and paste it into the `htdocs` directory of your XAMPP installation:
- **Windows**: `C:\xampp\htdocs\FoodMart`
- **macOS/Linux**: Check your local XAMPP/LAMP installation path.

### 4. Start XAMPP Services
- Open the **XAMPP Control Panel**.
- Start the **Apache** and **MySQL** modules.

---

## 🗄️ Database Setup & Configuration

The project uses a MySQL database. Follow these details for a manual or automatic setup.

### 1. Connection Settings
The database connection is managed in `includes/db_connection.php`.
- **Host**: `localhost`
- **User**: `root`
- **Password**: `""` (Empty by default in XAMPP)
- **Database Name**: `food_mart`

### 2. Manual Database Creation
If you prefer setting it up manually via **phpMyAdmin**:
1. Go to `http://localhost/phpmyadmin`.
2. Create a database named `food_mart`.
3. Import the `database.sql` file.

### 3. Database Schema (SQL Queries)
Here is the structure of the tables used in this project:

#### 👥 Users Table
```sql
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### 📦 Products Table
```sql
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    price VARCHAR(50),
    quantity_label VARCHAR(50),
    rating VARCHAR(10),
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### 🛒 Cart Table
```sql
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_title VARCHAR(255) NOT NULL,
    product_image VARCHAR(255),
    product_price VARCHAR(50),
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### ❤️ Favorites (Wishlist) Table
```sql
CREATE TABLE IF NOT EXISTS favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_title VARCHAR(255) NOT NULL,
    product_image VARCHAR(255),
    product_price VARCHAR(50),
    product_rating VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### 📋 Orders Table
```sql
CREATE TABLE IF NOT EXISTS orders (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    order_status VARCHAR(50) DEFAULT 'Pending',
    payment_method VARCHAR(50) NOT NULL,
    shipping_address TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### 🛍️ Order Items Table
```sql
CREATE TABLE IF NOT EXISTS order_items (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    order_id INT(11) NOT NULL,
    product_id INT(11) DEFAULT 0,
    product_name VARCHAR(255) NOT NULL,
    quantity INT(11) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
```

#### 📩 Subscribers Table
```sql
CREATE TABLE IF NOT EXISTS subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) DEFAULT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 📧 Email Service Setup (PHPMailer)

The project uses **PHPMailer** to handle email notifications (e.g., for subscriptions or contact forms). To make it work, you need to configure your SMTP settings.

### 1. Configuration File
Open `includes/mailer.php`.

### 2. Setup SMTP (Gmail Example)
If you are using Gmail, follow these steps:
1.  **Enable 2-Step Verification** on your Google Account.
2.  **Generate an App Password**:
    - Go to your Google Account settings -> Security.
    - Search for "App Passwords".
    - Select "Mail" and "Other (Custom name)" (e.g., FoodMart).
    - Copy the generated **16-character password**.
3.  **Update mailer.php**:
    ```php
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->Username   = 'your-email@gmail.com';  // Your Gmail address
    $mail->Password   = 'your-app-password';     // The 16-character code
    $mail->Port       = 587;   
    $mail->setFrom('your-email@gmail.com', 'FoodMart');
    ```

### 3. Verification
Once configured, features like the newsletter subscription in the footer will be able to send emails to users.

---

## 🚀 Final Step: Run the Project
Once the database and scripts are initialized, access the application at:
👉 **[http://localhost/FoodMart/index.php](http://localhost/FoodMart/index.php)**

---

## 📁 Project Structure

```text
FoodMart/
├── actions/            # Backend PHP logic for cart, wishlist, etc.
├── css/                # Stylesheets (Bootstrap, Plugins, Custom)
├── images/             # Product images and UI assets
├── includes/           # Header, Footer, DB Connection, Mailer
├── js/                 # Client-side scripts (Auth, Cart, Wishlist)
├── database.sql        # Initial database schema
├── setup_products.php  # Database initialization script
├── shop.php            # Main product listing page
└── index.php           # Landing/Home page
```

---

## 📜 Credits & Attribution

Developed by **Preet Dudhat**.

---

## 📄 License

Free for both personal and commercial use under the attribution terms of TemplatesJungle.................................................................
