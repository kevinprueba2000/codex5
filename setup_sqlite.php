<?php
// Simple script to create a lightweight SQLite database for tests

$path = __DIR__ . '/data/test.sqlite';

$pdo = new PDO('sqlite:' . $path);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Users table
$pdo->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    email TEXT UNIQUE,
    password TEXT,
    first_name TEXT,
    last_name TEXT,
    role TEXT DEFAULT 'customer',
    status TEXT DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);");

// Categories table
$pdo->exec("CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    slug TEXT UNIQUE,
    status TEXT DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);");

// Products table
$pdo->exec("CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    price REAL,
    stock_quantity INTEGER DEFAULT 0,
    category_id INTEGER,
    images TEXT,
    status TEXT DEFAULT 'active',
    slug TEXT UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);");

// Orders table
$pdo->exec("CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    total_amount REAL,
    status TEXT DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);");

// Insert basic data if empty
$count = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
if ($count == 0) {
    $pdo->exec("INSERT INTO users (username,email,password,first_name,last_name,role) VALUES('admin','admin@alquimiatechnologic.com','password','Admin','AlquimiaTech','admin');");
    $pdo->exec("INSERT INTO categories (name,slug) VALUES ('General','general');");
    $pdo->exec("INSERT INTO products (name,price,category_id,slug) VALUES('Demo Product',9.99,1,'demo-product');");
}

echo "SQLite database ready at $path\n";
