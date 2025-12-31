<?php
// pages/about.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>About Us | Sameer Motorsport</title>
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

/* DOTTED GRID BACKGROUND */
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

    
/* HERO */
.about-hero{
  min-height:100vh;
  display:flex;
  flex-direction:column;
  justify-content:center;
  align-items:center;
  padding-top:120px;
  text-align:center;
}

.hero-img{
  width:360px;
  height:360px;
  border-radius:18px;
  overflow:hidden;
  box-shadow:0 0 40px #00c8ff44;
  margin-bottom:30px;
}

.hero-img img{
  width:100%;
  height:100%;
  object-fit:cover;
}

.about-hero h1{
  font-size:42px;
  margin-bottom:10px;
}

.about-hero p{
  font-size:17px;
  opacity:0.85;
}

/* WHO WE ARE */
.section{
  padding:80px 10%;
  max-width:1200px;
  margin:auto;
}

.who{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:50px;
  align-items:center;
}

.who h2{
  color:#ff3c00;
  margin-bottom:15px;
}

.who p{
  line-height:1.8;
  opacity:0.9;
}

.who-img{
  border-radius:16px;
  overflow:hidden;
  box-shadow:0 0 30px #00c8ff44;
}

.who-img img{
  width:100%;
}

/* HIGHLIGHTS */
.highlights{
  text-align:center;
}

.highlights h2{
  color:#ff3c00;
  margin-bottom:40px;
}

.cards{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:30px;
}

.card{
  background:#0d0d0d;
  padding:30px;
  border-radius:14px;
  transition:.3s;
}

.card:hover{
  transform:translateY(-8px);
  box-shadow:0 0 25px #00c8ff44;
}

.card h3{
  margin-bottom:10px;
  color:#ff3c00;
}

.card p{
  font-size:14px;
  opacity:0.85;
}

/* FOOTER */
footer{
  text-align:center;
  padding:25px;
  font-size:13px;
  opacity:0.6;
}

/* RESPONSIVE */
@media(max-width:900px){
  .who{ grid-template-columns:1fr; text-align:center; }
}
</style>
</head>

<body>

<?php include '../includes/header.php'; ?>


<!-- HERO -->
<section class="about-hero">
  <div class="hero-img">
    <img src="../assets/images/about-hero.png" alt="Racing">
  </div>
  <h1>Our Racing Journey</h1>
  <p>Where passion meets engineering excellence</p>
</section>

<!-- WHO WE ARE -->
<section class="section who">
  <div>
    <h2>Who We Are</h2>
    <p>
        We are a team of motorsport enthusiasts dedicated to promoting competitive Go-Kart racing across India. Our mission is to bring together young engineers, drivers, and innovators to design, build, and race high-performance karts.Every year, we organize national-level Go-Kart racing events that challenge students to push their creativity and engineering excellence while ensuring safety and sportsmanship.

Our events are recognized for professional standards, expert judging panels, and a passionate community that truly lives for speed.
    </p>
  </div>

  <div class="who-img">
    <img src="../assets/images/about-bg2.png" alt="Karting">
  </div>
</section>

<!-- HIGHLIGHTS -->
<section class="section highlights">
  <h2>Our Highlights</h2>

  <div class="cards">
    <div class="card">
      <h3>🔧Innovation & Engineering</h3>
      <p>Encouraging cutting-edge kart design with real-world engineering exposure.</p>
    </div>

    <div class="card">
      <h3>👨‍🏫 Expert Guidance</h3>
      <p>Mentorship from experienced racing professionals and industry experts.</p>
    </div>

    <div class="card">
      <h3>🌎 Growing Community</h3>
      <p>A fast-growing motorsport family of racers, engineers, and enthusiasts.</p>
    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
<script>
function toggleMenu() {
  document.getElementById('navMenu').classList.toggle('active');
}
</script>

</body>
</html>
