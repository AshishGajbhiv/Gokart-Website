<?php
require '../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: events.php");
    exit;
}

$event_id = intval($_GET['id']);

// fetch event
$event = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM events WHERE id = $event_id")
);

if (!$event) {
    echo "Event not found";
    exit;
}

// fetch images
$images = mysqli_query(
    $conn,
    "SELECT image_name FROM event_images WHERE event_id = $event_id"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $event['title'] ?> | Event Details</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
  margin:0;
  font-family:Poppins,sans-serif;
  background:#050505;
  color:#fff;
}

/* dotted background */
body::before{
  content:"";
  position:fixed;
  inset:0;
  background-image:radial-gradient(#ffffff22 1px, transparent 1px);
  background-size:22px 22px;
  z-index:-1;
}

.container{
  padding:120px 6% 60px;
  max-width:1200px;
  margin:auto;
}

.event-title{
  color:#ff3c00;
  margin-bottom:20px;
}

/* slider */
.slider{
  width:100%;
  max-width:600px;
  aspect-ratio:1 / 1;
  position:relative;
  overflow:hidden;
  border-radius:18px;
  box-shadow:0 0 30px #000;
}

.slider img{
  position:absolute;
  inset:0;
  width:100%;
  height:100%;
  object-fit:cover;
  opacity:0;
  transition:opacity 1s ease;
}

.slider img.active{
  opacity:1;
}

/* layout */
.details{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:40px;
  margin-top:30px;
}

.meta{
  opacity:0.9;
  margin-bottom:20px;
}

.meta span{
  display:block;
  margin-bottom:8px;
}

.btn{
  display:inline-block;
  background:#ff3c00;
  color:#fff;
  padding:12px 22px;
  border-radius:8px;
  text-decoration:none;
  margin-right:10px;
}

/* mobile */
@media(max-width:800px){
  .details{
    grid-template-columns:1fr;
  }
  .slider{
    margin:auto;
  }
}

/* navbar */
header {
  width: 90%;
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
.slider{
  box-shadow:0 0 35px #00c8ff44;
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

<h1 class="event-title"><?= $event['title'] ?></h1>

<div class="details">

  <!-- IMAGE SLIDER -->
  <div class="slider" id="slider">
  <?php
    $first = true;
    mysqli_data_seek($images, 0); // reset pointer (safe)
    while($img = mysqli_fetch_assoc($images)):
  ?>
    <img src="../uploads/event_images/<?= $img['image_name'] ?>"
         class="<?= $first ? 'active' : '' ?>">
  <?php
    $first = false;
    endwhile;
  ?>
</div>


  <!-- EVENT INFO -->
  <div>
    <div class="meta">
      <span>📅 <strong>Date:</strong> <?= date("d M Y", strtotime($event['event_date'])) ?></span>
      <span>💰 <strong>Fee:</strong> ₹<?= $event['registration_fee'] ?></span>
    </div>

    <p><?= nl2br($event['description']) ?></p>

    <br>

    <a href="../uploads/event_rulebook/<?= $event['rulebook_pdf'] ?>" class="btn" download>
      📄 Download Rulebook
    </a>

    <a href="register.php?event_id=<?= $event['id'] ?>" class="btn">
  🏁 Register Now
</a>

  </div>

</div>

</div>
<?php include '../includes/footer.php'; ?>
<script>
const images = document.querySelectorAll('#slider img');
let i = 0;
if(images.length){
  images[0].classList.add('active');
  setInterval(()=>{
    images[i].classList.remove('active');
    i = (i+1) % images.length;
    images[i].classList.add('active');
  },2000);
}

function toggleMenu() {
  document.getElementById('navMenu').classList.toggle('active');
}
</script>
</body>
</html>
