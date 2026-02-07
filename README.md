# picBook - images and books store

an store for selling and purchase an images and books, the system add a "picbook" watermark on any product for copy right, there are three types of users (Seller, buyer, admin), 
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
- Adding watermark on all products for copy rights.


<div>
<img width="1845" height="879" alt="products page" src="https://github.com/user-attachments/assets/4025f72b-e520-4c36-8065-5797cdc395e9" />
<img width="1828" height="861" alt="seller dashboard" src="https://github.com/user-attachments/assets/19041998-1e4d-40d8-93f4-f0406790e8e1" />
<img width="1813" height="850" alt="product details for seller" src="https://github.com/user-attachments/assets/47fa78ee-c2d8-48cd-a811-3068fc05b3b9" />
<img width="1838" height="864" alt="images page" src="https://github.com/user-attachments/assets/2595677a-dba0-4941-b4cb-064774d81963" />
<img width="1823" height="854" alt="comments page" src="https://github.com/user-attachments/assets/d74345fe-d982-437a-9bab-b3562c030085" />
<img width="1828" height="863" alt="cart page" src="https://github.com/user-attachments/assets/10fb31ac-a483-45ed-9c70-27f884962547" />
<img width="1909" height="970" alt="payment page" src="https://github.com/user-attachments/assets/5fef5f1f-81b9-44b9-af3b-0b6c2fc9bede" />
<img width="1823" height="849" alt="product details for buyer" src="https://github.com/user-attachments/assets/6406f866-24d5-4272-bc3c-2da313017ea1" />

</div>
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
