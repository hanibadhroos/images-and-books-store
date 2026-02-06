# picBook - images and books store

an store for selling and purchase an images and books, there are three types of users (Seller, buyer, admin), 
Buyer can buy an image or book and pay using PayPal gatway, and he can review any product.
Seller can add, edit, delete and manage his products, he can show his data into seller dashboard, he can withdraw his money to his PayPal account.
Admin can manage all users and products.

---

## 🚀 Features

- User Authentication (Email Verification)
- Multi-language Support (Arabic & English)
- Seller Dashboard
- REST API, and Payment Integration
- Withdraw money of store to PayPal seller account.
<img width="1845" height="979" alt="Products page" src="https://github.com/user-attachments/assets/9d6ab56a-e883-4c69-bb59-14d5dd9aa48c" />

---

## 🛠 Tech Stack

**Backend:** Laravel 11  
**Frontend:** Vue.js 3  
**Database:** MySQL  
**Other:** Bootstrap, Axios

---

## ⚙️ Installation

```bash
git clone https://github.com/hanibadhroos/images-and-books-store.git
cd project-name
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
npm run dev
