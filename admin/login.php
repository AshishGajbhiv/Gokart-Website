<?php
session_start();
require '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");

    if (mysqli_num_rows($result) === 1) {

        $admin = mysqli_fetch_assoc($result);

        // ✅ VERY IMPORTANT LINE
        if (password_verify($password, $admin['password'])) {

            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];

            header("Location: dashboard.php");
            exit;

        } else {
            $error = "Invalid email or password";
        }

    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{
  background:#050505;
  display:flex;
  justify-content:center;
  align-items:center;
  height:100vh;
  color:#fff;
  font-family:Poppins,sans-serif;
}

/* navbar */
header {
  width: 80%;
  padding: 18px 8%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: rgba(0,0,0,0.75);
  backdrop-filter: blur(10px);
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1000;
}

.logo {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 700;
  font-size: 20px;
}

.logo img {
  width: 80px;
}

nav ul {
  display: flex;
  list-style: none;
  gap: 26px;
  align-items: center;
}

nav ul li a {
  text-decoration: none;
  color: #fff;
  font-size: 15px;
}

nav ul li a:hover {
  color: #ff3c00;
}

.nav-btn {
  padding: 8px 16px;
  background: #ff3c00;
  border-radius: 6px;
  color: #fff !important;
}

.menu-toggle {
  display: none;
  font-size: 28px;
  cursor: pointer;
}

/* Mobile */
@media (max-width: 768px) {
  nav ul {
    position: absolute;
    top: 70px;
    right: -100%;
    width: 100%;
    flex-direction: column;
    background: #000;
    padding: 30px 0;
    transition: 0.4s;
  }

  nav ul.active {
    right: 0;
  }

  .menu-toggle {
    display: block;
  }
}



.login-box{
  background:#1b1b1b;
  padding:30px;
  border-radius:16px;
  width:320px;
}
input{
  width:100%;
  padding:10px;
  margin-top:12px;
  border:none;
  border-radius:6px;
}
button{
  width:100%;
  margin-top:20px;
  padding:10px;
  background:#ff3c00;
  border:none;
  color:#fff;
  border-radius:6px;
}
.error{
  color:red;
  margin-top:10px;
  font-size:14px;
}
</style>
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="login-box">
<h2>Admin Login</h2>

<form method="post">
<input type="email" name="email" placeholder="Admin Email" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Login</button>
</form>

<?php if($error): ?>
<p class="error"><?= $error ?></p>
<?php endif; ?>
</div>
<script>
function toggleMenu() {
  document.getElementById('navMenu').classList.toggle('active');
}
</script>
</body>
</html>
