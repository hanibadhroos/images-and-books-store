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
<img alt="Products page" src="https://github.com/user-attachments/assets/9d6ab56a-e883-4c69-bb59-14d5dd9aa48c" />
<img  alt="Screenshot (158)" src="https://github.com/user-attachments/assets/af7f830a-7634-40a4-bee9-7e8cd7b50ff4" />
<img  alt="Screenshot (162)" src="https://github.com/user-attachments/assets/3f3f7003-00e5-4208-b50f-5c2a6d17ced8" />
<img  alt="Screenshot (159)" src="https://github.com/user-attachments/assets/58205a7c-e4ae-41a6-a201-531f96bc2e4e" />
<img alt="Screenshot (157)" src="https://github.com/user-attachments/assets/d4007242-0cd0-4691-9ecc-36036e445988" />

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
