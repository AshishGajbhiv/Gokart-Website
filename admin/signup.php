<?php
session_start();
require '../includes/db.php';

define('ADMIN_SECRET_KEY', 'SANDD_ADMIN_2025');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['admin_otp_verified']) || $_SESSION['admin_otp_verified'] !== true) {
        $error = "OTP not verified";
    } else {

        $email = $_POST['email'];
        $password = $_POST['password'];
        $secret = $_POST['secret_key'];

        if ($secret !== ADMIN_SECRET_KEY) {
            $error = "Invalid Admin Secret Key";
        } else {

            $check = mysqli_query($conn, "SELECT id FROM admins WHERE email='$email'");
            if (mysqli_num_rows($check) > 0) {
                $error = "Admin already exists";
            } else {

                $hashed = password_hash($password, PASSWORD_DEFAULT);
                mysqli_query($conn,
                    "INSERT INTO admins (email, password)
                     VALUES ('$email', '$hashed')"
                );

                unset($_SESSION['admin_otp_verified']);

                header("Location: login.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Signup | Sameer Motorsport</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
  background:#050505;
  color:#fff;
  font-family:Poppins, sans-serif;
}
body::before{
  content:"";
  position:fixed;
  inset:0;
  background-image:radial-gradient(#ffffff22 1px, transparent 1px);
  background-size:22px 22px;
  z-index:-1;
}

.container{
  max-width:420px;
  margin:120px auto;
  background:#1b1b1b;
  padding:30px;
  border-radius:16px;
  box-shadow:0 0 30px #000;
}

h2{
  text-align:center;
  color:#ff3c00;
  margin-bottom:20px;
}

input, button{
  width:100%;
  padding:12px;
  margin-bottom:12px;
  border:none;
  border-radius:8px;
  font-size:14px;
}

input{
  background:#111;
  color:#fff;
}

button{
  background:#ff3c00;
  color:#fff;
  cursor:pointer;
}

button:disabled{
  opacity:0.6;
  cursor:not-allowed;
}

.error{
  color:#ff4d4d;
  text-align:center;
  margin-bottom:10px;
}

.hidden{
  display:none;
}
</style>
</head>

<body>

<div class="container">
<h2>Admin Signup</h2>

<?php if($error): ?>
<div class="error"><?= $error ?></div>
<?php endif; ?>

<input type="email" id="email" placeholder="Admin Email" required>
<button onclick="sendOTP()">Send OTP</button>

<input type="text" id="otp" placeholder="Enter OTP" class="hidden">
<button onclick="verifyOTP()" id="verifyBtn" class="hidden">Verify OTP</button>

<form method="POST" id="signupForm" class="hidden">
  <input type="hidden" name="email" id="finalEmail">
  <input type="password" name="password" placeholder="Password" required>
  <input type="text" name="secret_key" placeholder="Admin Secret Key" required>
  <button type="submit">Create Admin</button>
</form>

</div>

<script>
function sendOTP(){
  let email = document.getElementById('email').value;
  fetch('../pages/send_admin_otp.php', {
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:'email='+email
  }).then(r=>r.text()).then(alert);
}

function verifyOTP(){
  let otp = document.getElementById('otp').value;
  fetch('../pages/verify_admin_otp.php', {
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:'otp='+otp
  }).then(r=>r.text()).then(res=>{
    if(res.trim()==='verified'){
      document.getElementById('signupForm').classList.remove('hidden');
      document.getElementById('finalEmail').value = document.getElementById('email').value;
    }else{
      alert('Invalid OTP');
    }
  });
}

document.getElementById('email').addEventListener('input', ()=>{
  document.getElementById('otp').classList.remove('hidden');
  document.getElementById('verifyBtn').classList.remove('hidden');
});
</script>

</body>
</html>
