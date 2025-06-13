# 📚 Bookstore Web Application

This is a full-stack online bookstore web application built with **PHP**, **MySQL**, **HTML/CSS/JavaScript**, and enhanced with basic AI features using the `askAI` and `aiMoodRecommender` logic. It supports both **user** and **admin** roles with rich functionality such as book browsing, purchase management, AI book recommendations, and profile updates.

---

## 🔍 Features

### ✅ General
- User and Admin sign-up/sign-in
- Secure session handling
- Role-based redirection

### 🛍️ User Features
- Browse and search for books
- Add books to cart and wishlist
- Complete purchases and view order history
- View personalized AI recommendations
- Edit profile and change password

### 🛠️ Admin Features
- Add, edit, or delete books
- View all purchases and manage order statuses
- View user profiles and reviews

---

## 🖼️ Screenshots

### 1. 🔐 Sign In / Sign Up Pages
[Screenshot goes here]

### 2. 🏠 Homepage / Book Listing
[Screenshot goes here]

### 3. 🛒 Cart and Wishlist Pages
[Screenshot goes here]

### 4. 🤖 AI Recommendations Page
[Screenshot goes here]

### 5. 🧾 Purchase History
[Screenshot goes here]

### 6. 🔧 Admin Dashboard (Manage Books)
[Screenshot goes here]

---

## 🗂️ Project Structure

### 📁 css/ – Stylesheets
- style.css — Main styling for the site

### 📁 images/ – Book covers, UI illustrations, and user images
- account/
- addToCart/
- argon/
- cart/
- confirmedPurchase/
- editProfile/
- logoutAdmin/
- purchasesAdmin/
- removeFromWishlist/
- signinAdmin/
- signinLogic/
- updateProfile/
- wishlist/

### 📁 js/ – JavaScript frontend logic
- addBookReviews.js
- addToWishlist.js
- askAI.js
- changePassword.js
- deleteBooks.js
- failedPurchase.js
- processPurchase.js
- recommendations.js
- sessionCheck.js
- signinAdminLogic.js
- signup.js
- updatePurchaseStatus.js

### 📁 sql/ – Server-side PHP logic (named “sql”)
- addBooks.php
- adminBookDetails.php
- book.php
- changePasswordLogic.php
- deleteReviews.php
- index.php
- purchase.php
- removeExpiredItems.php
- sessionCheckAdmin.php
- signinBook.php
- signupLogic.php
- user.php

### 👥 User Features
- books.php
- purchases.php
- wishlist.php
- cart.php
- addToCart.php
- removeFromCart.php
- removeFromWishlist.php
- editProfile.php
- updateProfile.php
- changePassword.php
- users.php

### 🧑‍💼 Admin Features
- purchasesAdmin.php
- updatePurchaseStatus.php
- addBooksLogic.php
- editBooks.php
- deleteBooks.php
- logoutAdmin.php
- signinAdminLogic.php

### 🤖 AI Features
- aiMoodRecommender.php
- askAI.php
- recommendations.php

### 🔐 Authentication
- signin.php
- signup.php
- signinBooks.php
- signinLogic.php
- signupLogic.php
- logout.php
- sessionCheck.php
- sessionCheckAdmin.php

### ⚙️ Configuration
- config.php

---

## 🧠 AI Features

- **askAI.php** — A basic AI assistant that helps users find books by genre, mood, or preference.
- **aiMoodRecommender.php** — Uses user mood or keyword input to suggest books.

---

## 🧪 Technologies Used

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **AI API**: Optional integration for smart recommendations

---

## 🚀 How to Run the Project

Follow the steps below to set up and run the Bookstore Web Application on your local environment:

---

### 1. 📥 Clone the Repository

Open a terminal or Git Bash and run:

```bash
git clone https://github.com/your-username/bookstore-app.git
cd bookstore-app
```

---

### 2. 🛠️ Set Up the Database

1. Open **phpMyAdmin** or use the **MySQL CLI**.
2. Create a new database (e.g. `bookstore_db`).
3. Import the required `.sql` files from the `/sql` directory, such as:

   - `user.sql`
   - `book.sql`
   - `purchase.sql`
   - `review.sql`
   - etc.

These files will create the necessary tables and insert any initial data.

---

### 3. ⚙️ Configure Database Connection

Open `config.php` and update the database settings:

```php
$conn = new PDO("mysql:host=localhost;dbname=bookstore_db", "root", "");
```

Make sure your credentials match your **phpMyAdmin** or **MySQL** setup.

---

### 4. ▶️ Run the App Locally

Use **XAMPP**, **WAMP**, **LAMP**, or another PHP local server.

1. Place the project folder inside your `htdocs` (XAMPP) or `www` (WAMP) directory.
2. Start **Apache** and **MySQL** from your server control panel.
3. Open your browser and navigate to:

```
http://localhost/bookstore-app/signinBooks.php
```

This is the main entry point for users and admins.

---

### 5. 🧪 Test the Features

- Sign up as a new user or log in as an admin (create one manually in the database if needed).
- Browse books, add to cart or wishlist.
- Complete a purchase and view order history.
- Use the admin dashboard to manage books and orders.
- Test AI features on the recommendations page.

---

### ✅ Optional: Enable AI Features

If your app uses APIs for AI recommendations (e.g., OpenAI or Cohere), you may need:

- An API key
- Proper endpoint integration in `askAI.php` or `aiMoodRecommender.php`

Make sure these files have proper error handling and access credentials.

---

### 🧹 Troubleshooting

- Make sure Apache and MySQL are running.
- Check your database credentials and ensure the DB was imported properly.
- Inspect the browser console and PHP error logs for runtime issues.

---

### 🧑‍💻 Contributors
Dren Nitaj – Developer and Designer
