<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Check Registration Status</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body{
  background:#050505;
  color:#fff;
  font-family:Poppins,sans-serif;
}
body::before{
  content:"";
  position:fixed;
  inset:0;
  background-image:radial-gradient(#ffffff22 1px, transparent 1px);
  background-size:22px 22px;
  z-index:-1;
}


/* navbar */
header {
  width: 85%;
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


.container{
  max-width:600px;
  margin:140px auto;
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

input{
  width:100%;
  padding:12px;
  border:none;
  border-radius:8px;
  margin-bottom:14px;
}

button{
  width:100%;
  padding:12px;
  background:#ff3c00;
  border:none;
  border-radius:8px;
  color:#fff;
  cursor:pointer;
}

.status-card{
  background:#111;
  padding:20px;
  border-radius:14px;
  margin-top:16px;
  box-shadow:0 0 20px #000;
}

.status-card h3{
  margin-bottom:8px;
}

.approved{ color:#5cb85c; }
.pending{ color:#f0ad4e; }
.rejected{ color:#d9534f; }

.reason{
  margin-top:8px;
  font-size:14px;
  opacity:0.9;
}

footer{
  text-align:center;
  padding:25px;
  font-size:13px;
  opacity:0.6;
}
</style>
</head>

<body>

<?php include '../includes/header.php'; ?>

<div class="container">
<h2>Check Registration Status</h2>

<input type="email" id="email" placeholder="Enter your email" required>
<button onclick="checkStatus()">Check Status</button>

<div id="result"></div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
function checkStatus(){
  let email = document.getElementById('email').value;
  if(email === ''){
    alert('Enter email');
    return;
  }

  fetch('fetch_status.php', {
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:'email=' + encodeURIComponent(email)
  })
  .then(res => res.text())
  .then(data => {
    document.getElementById('result').innerHTML = data;
  });
}
</script>

</body>
</html>
