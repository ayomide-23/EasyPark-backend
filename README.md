# 🚗 EasyPark Backend

This is the PHP + MySQL backend for the **EasyPark Parking Management System**. It handles user authentication, registration, bookings, notifications, slot updates, and more.

- 🔗 **Frontend:** [https://easy-park-frontend-aderinto-ayomides-projects.vercel.app](https://easy-park-frontend-aderinto-ayomides-projects.vercel.app)
- 🔗 **Backend:** [https://easypark-backend-2toe.onrender.com](https://easypark-backend-2toe.onrender.com/register.php)

---

## 🛠 Tech Stack

- **Backend Language:** PHP
- **Database:** MySQL
- **API Format:** JSON
- **Hosting:** Render (Docker-based)
- **Security:** Input validation, password hashing, token-based auth, CORS


---

## ⚙️ Local Setup Instructions

1. **Clone the repo**
```bash
git clone https://github.com/ayomide-23/EasyPark-backend.git
cd EasyPark-backend
```
2. **Create a MySQL Database**
   Create a database named easypark (or your preferred name).Import your SQL schema with tables:users, bookings, parking_slots, notifications, etc.
   
3. **Set Up Database Connection**
   Open db_connect.php and update with your DB config:
   <pre>
     ```php
          <?php
          $host = "localhost";
          $user = "root";
           $password = "";
           $dbname = "easypark";
           $conn = new mysqli($host, $user, $password, $dbname);
           if ($conn->connect_error) {
           die("Connection failed: " . $conn->connect_error);
           }
           ?>
   </pre>
4. **Test It**
   Create a test endpoint (e.g. test.php):
   <pre>
     ```php
           <?php
             include 'db_connect.php';
             echo json_encode(["success" => true, "message" => "Backend working"]);
   </pre>
