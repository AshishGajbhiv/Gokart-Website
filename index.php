<?php
// index.php
?>
<?php
require 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sameer Motorsport | GoKart Racing</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-color: #050505;
      color: #fff;
      overflow-x: hidden;
    }

    /* ===== GRID DOT BACKGROUND ===== */
    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background-image: radial-gradient(#ffffff33 1px, transparent 1px);
      background-size: 22px 22px;
      z-index: -1;
    }

    /* ===== NAVBAR ===== */
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


    /* ===== HERO SECTION ===== */
    .hero {
      min-height: 100vh;
      padding: 140px 8% 60px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      align-items: center;
      gap: 40px;
    }

    .hero-text h1 {
      font-size: 52px;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .hero-text p {
      font-size: 18px;
      opacity: 0.9;
      margin-bottom: 30px;
    }

    .hero-text a {
      display: inline-block;
      padding: 14px 30px;
      background: #ff3c00;
      color: #fff;
      border-radius: 30px;
      text-decoration: none;
      font-weight: 600;
      transition: 0.3s;
    }

    .hero-text a:hover {
      background: #ff5a2a;
    }

    .hero-img img {
      width: 100%;
      max-width: 500px;
      
    }
    .hero-img img:hover{
      filter: drop-shadow(0 0 40px #00ffff33);
      transition: all ease 0.5s;
      scale: 1.1;
    }
    /* ===== RESPONSIVE ===== */
    @media(max-width: 900px) {
      .hero {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .hero-img {
        order: -1;
      }
    }

    @media(max-width: 768px) {
      nav ul {
        position: absolute;
        top: 70px;
        right: -100%;
        width: 100%;
        flex-direction: column;
        background: #000;
        padding: 25px 0;
        transition: 0.4s;
      }

      nav ul.active {
        right: 0;
      }

      .menu-toggle {
        display: block;
      }
    }
    footer{
  text-align:center;
  padding:25px;
  font-size:13px;
  opacity:0.6;
}
.notice-bar{
  margin-top: 8rem;
  background: rgba(0, 0, 0, 0.455);
  color: crimson;
  padding:10px 0;
  font-weight:600;
  font-size:16px;
}
.notice-bar marquee{
  width:100%;
}

  </style>
</head>

<body>

<?php include 'includes/header.php'; ?>

<?php
$notice = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT notice_text FROM notices WHERE is_active=1 ORDER BY id DESC LIMIT 1")
);
?>

<?php if ($notice): ?>
<div class="notice-bar">
  <marquee scrollamount = "14"><?= htmlspecialchars($notice['notice_text']) ?></marquee>
</div>
<?php endif; ?>

<section class="hero">
  <div class="hero-text">
    <h1>Experience the Thrill of GoKart Racing</h1>
    <p>Register your team now and race with the best on professional tracks built for pure adrenaline.</p>
  </div>

  <div class="hero-img">
    <!-- Replace kart.png with your racing image -->
    <img src="assets/images/mainbg.jpg" alt="GoKart">
  </div>
</section>


<?php include 'includes/footer.php'; ?>
<script>
function toggleMenu() {
  document.getElementById('navMenu').classList.toggle('active');
}
</script>


</body>
</html>
