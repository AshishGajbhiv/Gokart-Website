<?php
require '../includes/db.php';

/* =========================================
   🔒 HARD BLOCK GENERAL REGISTRATION
   ========================================= */

// 1. event_id must exist
if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    header("Location: events.php");
    exit;
}

$event_id = (int) $_GET['event_id'];

// 2. event must exist
$event = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT 
            title,
            registration_status,
            qr_code,
            bank_name,
            account_holder,
            account_number,
            ifsc_code
         FROM events 
         WHERE id = $event_id"
    )
);

if (!$event) {
    header("Location: events.php");
    exit;
}

// 3. registration closed check
if ($event['registration_status'] === 'CLOSED') {
    echo "
    <div style='
      background:#1b1b1b;
      padding:25px;
      border-radius:14px;
      color:#ff3c00;
      text-align:center;
      margin:160px auto;
      max-width:500px;
      box-shadow:0 0 30px #000;
      font-family:Poppins,sans-serif;
    '>
      <h2>Registration Closed</h2>
      <p>This event is no longer accepting registrations.</p>
      <a href='events.php' style='
        display:inline-block;
        margin-top:20px;
        padding:10px 16px;
        background:#ff3c00;
        color:#fff;
        border-radius:8px;
        text-decoration:none;
      '>View Other Events</a>
    </div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Event Registration | S & D Motorsports</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#050505;color:#fff;}
body::before{
  content:"";position:fixed;inset:0;
  background-image:radial-gradient(#ffffff22 1px,transparent 1px);
  background-size:22px 22px;z-index:-1;
}
.container{
  max-width:480px;
  margin:140px auto 60px;
  background:#1b1b1b;
  padding:30px;
  border-radius:16px;
  box-shadow:0 0 30px #000;
}
h2{text-align:center;color:#ff3c00;margin-bottom:20px;}
label{font-size:14px;margin-top:14px;display:block;}
input,select{
  width:100%;padding:10px;margin-top:6px;
  border-radius:6px;border:none;
}
input[disabled]{opacity:0.5;}
.row{display:flex;gap:10px;}
button{
  margin-top:16px;padding:10px;
  border:none;border-radius:6px;
  background:#ff3c00;color:#fff;cursor:pointer;
}
button.secondary{background:#333;}
button:disabled{opacity:0.6;cursor:not-allowed;}
.payment-box{
  background:#2a2a2a;padding:15px;
  border-radius:12px;margin-top:20px;
  text-align:center;
}
.payment-box img{width:250px;margin:15px 0;}
.submit-btn{
  width:100%;margin-top:25px;
  background:#555;cursor:not-allowed;
}
/* navbar */
header {
  width: 100%;
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

</style>
</head>

<body>

<?php include '../includes/header.php'; ?>

<div class="container">
<h2><?= htmlspecialchars($event['title']) ?> Registration</h2>

<form id="registerForm" enctype="multipart/form-data">

<input type="hidden" name="event_id" value="<?= $event_id ?>">

<label>Email *</label>
<div class="row">
  <input type="email" name="email" id="emailField" required>
  <button type="button" id="sendOtpBtn" onclick="sendOTP()">Send OTP</button>
</div>

<label>Enter OTP *</label>
<div class="row">
  <input type="text" id="otpField" disabled>
  <button type="button" class="secondary" onclick="verifyOTP()">Verify OTP</button>
</div>

<label>Captain Name *</label>
<input name="captain_name" class="lock" disabled>

<label>Vice Captain Name *</label>
<input name="vice_captain_name" class="lock" disabled>

<label>Mobile *</label>
<input name="mobile" class="lock" disabled>

<label>College *</label>
<input name="college" class="lock" disabled>

<label>Vehicle Category *</label>
<select name="vehicle_category" class="lock" disabled>
  <option>Electrical</option>
  <option>IC</option>
</select>

<label>Team Name *</label>
<input name="team_name" class="lock" disabled>

<label>Upload Team Logo *</label>
<input type="file" name="team_logo" class="lock" disabled>

<!-- ===== PAYMENT SECTION ===== -->
<div class="payment-box">
  <h4>Pay Registration Fee</h4>

  <?php if (!empty($event['qr_code'])): ?>
    <img src="../uploads/event_qr/<?= htmlspecialchars($event['qr_code']) ?>">
  <?php endif; ?>

  <hr style="margin:15px 0;border-top:1px solid #444;">

  <?php if (!empty($event['bank_name'])): ?>
    <p><b>Bank Name:</b> <?= htmlspecialchars($event['bank_name']) ?></p>
    <p><b>Account Holder:</b> <?= htmlspecialchars($event['account_holder']) ?></p>
    <p><b>Account No:</b> <?= htmlspecialchars($event['account_number']) ?></p>
    <p><b>IFSC:</b> <?= htmlspecialchars($event['ifsc_code']) ?></p>
  <?php else: ?>
    <p style="color:#ff3c00;">Bank details not available</p>
  <?php endif; ?>
</div>

<p style="font-size:13px;opacity:0.7;text-align:center;margin-top:10px;color:lightblue;">
After payment, upload the screenshot below <br>(make sure transaction id is visible)
</p>

<label>Upload Payment Screenshot *</label>
<input type="file" name="payment_screenshot" class="lock" disabled>

<button class="submit-btn lock" disabled>Submit Registration</button>

</form>
</div>

<?php include '../includes/footer.php'; ?>

<script>
let otpSending=false;

function sendOTP(){
  if(otpSending) return;
  const email=emailField.value;
  if(!email) return alert('Enter email');

  otpSending=true;
  sendOtpBtn.disabled=true;
  sendOtpBtn.innerText='Sending...';

  fetch('send_otp.php',{
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:'email='+encodeURIComponent(email)
  })
  .then(r=>r.text())
  .then(msg=>{
    alert(msg);
    if(msg.toLowerCase().includes('otp')){
      otpField.disabled=false;
      sendOtpBtn.innerText='OTP Sent';
    } else {
      sendOtpBtn.disabled=false;
      sendOtpBtn.innerText='Send OTP';
      otpSending=false;
    }
  });
}

function verifyOTP(){
  fetch('verify_otp.php',{
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:'otp='+encodeURIComponent(otpField.value)
  })
  .then(r=>r.text())
  .then(msg=>{
    alert(msg);
    if(msg.toLowerCase().includes('verified')){
      document.querySelectorAll('.lock').forEach(el=>el.disabled=false);
      document.querySelector('.submit-btn').disabled=false;
      document.querySelector('.submit-btn').style.background='#ff3c00';
    }
  });
}

registerForm.addEventListener('submit',e=>{
  e.preventDefault();
  fetch('save_registration.php',{method:'POST',body:new FormData(registerForm)})
  .then(r=>r.text())
  .then(alert);
});
function toggleMenu() {
  document.getElementById('navMenu').classList.toggle('active');
}
</script>

</body>
</html>
