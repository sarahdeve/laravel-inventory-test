# Product Inventory App (Laravel + AJAX)

## Overview
This is a simple Laravel-based product inventory system that allows users to:
- Add products
- Edit products
- Delete products
- Store data in a JSON file
- Display products in a dynamic table using AJAX
- Calculate total value per product and overall sum

## Features
- Add product via AJAX
- Store data in JSON
- Display products with totals
- Sum of all product values

## Setup
1. Run `composer install`
2. Run `php artisan serve`
3. Open browser at http://127.0.0.1:8000

## Features

### Add Product
- Product name
- Quantity in stock
- Price per item
- Automatically calculates total value

### Edit Product
- Edit existing product without page refresh
- Updates stored JSON data

### Delete Product
- Remove product instantly using AJAX

###  Live Table
- Displays all products dynamically
- Ordered by latest entry
- Shows total sum of all products

###  Storage
- Uses JSON file storage (`storage/app/products.json`)
- No database required

---

## Tech Stack
- Laravel
- PHP
- JavaScript (AJAX / Fetch API)
- Bootstrap 5
- JSON file storage

---

## How to Run

1. Clone repository
bash
git clone <your-repo-url>