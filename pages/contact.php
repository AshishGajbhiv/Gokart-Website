<?php
// pages/contact.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contact Us | Sameer Motorsport</title>
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


/* MAIN SECTION */
.contact-wrapper{
  max-width:1200px;
  margin:140px auto 80px;
  padding:0 8%;
}

.contact-title{
  text-align:center;
  margin-bottom:50px;
}

.contact-title h1{
  color:#ff3c00;
  font-size:36px;
  margin-bottom:10px;
}

.contact-title p{
  opacity:0.85;
}

/* GRID */
.contact-grid{
  display:grid;
  grid-template-columns:1.2fr 0.8fr;
  gap:40px;
}

/* FORM */
.contact-form{
  background:#1b1b1b;
  padding:30px;
  border-radius:16px;
  box-shadow:0 0 30px #000;
}

.contact-form label{
  display:block;
  margin-top:15px;
  font-size:14px;
}

.contact-form input,
.contact-form textarea{
  width:100%;
  margin-top:6px;
  padding:12px;
  border:none;
  border-radius:8px;
}

.contact-form textarea{
  resize:none;
  height:120px;
}

.contact-form button{
  width:100%;
  margin-top:20px;
  padding:12px;
  border:none;
  border-radius:8px;
  background:#ff3c00;
  color:#fff;
  font-size:16px;
  cursor:pointer;
}

/* INFO BOXES */
.contact-info{
  display:flex;
  flex-direction:column;
  gap:20px;
}

.info-box{
  background:#1b1b1b;
  padding:25px;
  border-radius:16px;
  box-shadow:0 0 25px #000;
}

.info-box h3{
  color:#ff3c00;
  margin-bottom:8px;
  font-size:18px;
}

.info-box p{
  opacity:0.9;
  font-size:14px;
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
  .contact-grid{
    grid-template-columns:1fr;
  }
}
</style>
</head>

<body>

<?php include '../includes/header.php'; ?>

<section class="contact-wrapper">

  <div class="contact-title">
    <h1>CONTACT US</h1>
    <p>Have any questions or want to organize a racing event? We’d love to hear from you.</p>
  </div>

  <div class="contact-grid">

    <!-- LEFT FORM -->
    <div class="contact-form">
      <form id="contactForm">
        <label>Full Name</label>
        <input type="text" name="name" placeholder="Enter your name">

        <label>Email Address</label>
        <input type="email" name="email" placeholder="Enter your email">

        <label>Subject</label>
        <input type="text" name="subject" placeholder="Subject">

        <label>Message</label>
        <textarea name="message" placeholder="Write your message"></textarea>

        <button type="submit">Send Message</button>
      </form>
    </div>

    <!-- RIGHT INFO -->
    <div class="contact-info">

      <div class="info-box">
        <h3>📍 Address</h3>
        <p>Go-Kart Events<br>Pune, Maharashtra, India</p>
      </div>

      <div class="info-box">
        <h3>📞 Phone</h3>
        <p>+91 8484032852</p>
      </div>

      <div class="info-box">
        <h3>✉ Email</h3>
        <p>help.sandd@gmail.com</p>
      </div>

    </div>

  </div>
</section>

<footer>
  © 2025 S and D Motorsport • Built for Racing Enthusiasts
</footer>
<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('send_contact.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
        if (data.includes('Successfully')) {
            this.reset();
        }
    });
});
function toggleMenu() {
  document.getElementById('navMenu').classList.toggle('active');
}
</script>

</body>
</html>
