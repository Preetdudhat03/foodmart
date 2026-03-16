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

### 5. Database Setup
The project is designed to automatically create the database if it doesn't exist, but you can also set it up manually:
- Open your browser and go to `http://localhost/phpmyadmin`.
- (Optional) Create a new database named `food_mart`.
- Manual Import: Import the provided `database.sql` file into your `food_mart` database.

### 6. Initialize Tables and Products
Run the following setup scripts in your browser to populate the database with tables and sample data:
1. `http://localhost/FoodMart/setup_products.php` (Creates products table and adds initial stock).
2. `http://localhost/FoodMart/setup_orders.php` (Creates orders and order items tables).

### 7. Run the Project
Access the application at:
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

This template was originally provided by [TemplatesJungle](https://templatesjungle.com/).
Modified and enhanced by [Your Name/Preet].

---

## 📄 License

Free for both personal and commercial use under the attribution terms of TemplatesJungle.
