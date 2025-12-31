<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$totalReg = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM registrations")
)['total'];

$pendingReg = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM registrations WHERE status='PENDING'")
)['total'];

$approvedReg = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM registrations WHERE status='APPROVED'")
)['total'];

$totalEvents = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM events")
)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | Sameer Motorsport</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:'Poppins',sans-serif;
}

body{
  background:#050505;
  color:#fff;
}

body::before{
  content:"";
  position:fixed;
  inset:0;
  background-image:radial-gradient(#ffffff22 1px, transparent 1px);
  background-size:22px 22px;
  z-index:-1;
}

/* ================= HEADER ================= */
.header{
  padding:20px 8%;
  background:rgba(0,0,0,0.85);
  backdrop-filter:blur(10px);
  display:flex;
  justify-content:space-between;
  align-items:center;
  position:relative;
  z-index:3000;
}

.logo{
  font-size:16px;
  font-weight:600;
}

/* NAV LINKS (DESKTOP) */
.nav-links{
  display:flex;
  gap:16px;
}

.nav-links a{
  background:#ff3c00;
  color:#fff;
  padding:8px 16px;
  border-radius:8px;
  text-decoration:none;
  font-size:14px;
}

.nav-links .logout{
  background:#d9534f;
}

/* HAMBURGER */
.hamburger{
  display:none;
  font-size:28px;
  cursor:pointer;
}

/* ================= BACKDROP ================= */
.menu-backdrop{
  display:none;
  position:fixed;
  inset:0;
  background:rgba(0,0,0,0.65);
  z-index:2000;
}

.menu-backdrop.show{
  display:block;
}

/* ================= MAIN ================= */
.container{
  padding:60px 8%;
}

/* SUMMARY CARDS */
.summary{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:20px;
  margin-bottom:50px;
}

.summary-card{
  background:#1b1b1b;
  padding:25px;
  border-radius:14px;
  box-shadow:0 0 25px #000;
}

.summary-card h2{
  font-size:28px;
  color:#ff3c00;
}

.summary-card p{
  opacity:0.8;
  font-size:14px;
}

/* ACTION CARDS */
.actions{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:30px;
}

.card{
  background:#1b1b1b;
  padding:30px;
  border-radius:16px;
  box-shadow:0 0 30px #000;
  transition:0.3s;
}

.card:hover{
  transform:translateY(-8px);
}

.card h3{
  color:#ff3c00;
  margin-bottom:10px;
}

.card p{
  font-size:14px;
  opacity:0.85;
  margin-bottom:20px;
}

.card a{
  display:inline-block;
  padding:10px 18px;
  background:#ff3c00;
  color:#fff;
  text-decoration:none;
  border-radius:6px;
  font-size:14px;
}

/* ================= RESPONSIVE ================= */
@media (max-width:900px){
  .summary{
    grid-template-columns:repeat(2,1fr);
  }
}

@media (max-width:768px){

  .hamburger{
    display:block;
  }

  .nav-links{
    position:fixed;
    top:70px;
    right:20px;
    background:#111;
    width:220px;
    padding:18px;
    border-radius:16px;
    box-shadow:0 0 60px rgba(0,0,0,0.9);
    display:none;
    z-index:3000;
    flex-direction:column;
    gap:12px;
    isolation:isolate;
  }

  .nav-links a{
    display:block;
    text-align:center;
    padding:12px;
  }

  .nav-links.show{
    display:flex;
  }
}
</style>
</head>

<body>

<header class="header">
  <div class="logo">ADMIN</div>

  <div class="hamburger" onclick="toggleMenu()">☰</div>

  <nav class="nav-links" id="navMenu">
    <a href="/gokart/index.php">Home</a>
    <a href="/gokart/pages/events.php">Events</a>
    <a href="/gokart/admin/signup.php">Admin Signup</a>
    <a href="logout.php" class="logout">Logout</a>
  </nav>
</header>

<div id="menuBackdrop" class="menu-backdrop" onclick="closeMenu()"></div>

<div class="container">

  <div class="summary">
    <div class="summary-card">
      <h2><?= $totalEvents ?></h2>
      <p>Total Events</p>
    </div>

    <div class="summary-card">
      <h2><?= $totalReg ?></h2>
      <p>Total Registrations</p>
    </div>

    <div class="summary-card">
      <h2><?= $pendingReg ?></h2>
      <p>Pending Registrations</p>
    </div>

    <div class="summary-card">
      <h2><?= $approvedReg ?></h2>
      <p>Approved Registrations</p>
    </div>
  </div>

  <div class="actions">
    <div class="card">
      <h3>Manage Registrations</h3>
      <p>View, approve or reject team registrations and payment proofs.</p>
      <a href="event_cards.php">Open</a>
    </div>

    <div class="card">
      <h3>Manage Events</h3>
      <p>Edit or delete existing events listed on the website.</p>
      <a href="manage_events.php">Open</a>
    </div>

    <div class="card">
      <h3>Add New Event</h3>
      <p>Create a new event with images, rulebook and registration fee.</p>
      <a href="add_event.php">Add Event</a>
    </div>
    <div class="card">
  <h3>Manage Notice</h3>
  <p>Create announcement for upcoming events.</p>
  <a href="manage_notice.php">Open</a>
</div>
  </div>


</div>

<script>
function toggleMenu(){
  document.getElementById('navMenu').classList.toggle('show');
  document.getElementById('menuBackdrop').classList.toggle('show');
}

function closeMenu(){
  document.getElementById('navMenu').classList.remove('show');
  document.getElementById('menuBackdrop').classList.remove('show');
}

document.querySelectorAll('.nav-links a').forEach(link=>{
  link.addEventListener('click', closeMenu);
});
</script>

</body>
</html>
