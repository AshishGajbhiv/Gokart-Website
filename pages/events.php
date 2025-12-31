<?php
require '../includes/db.php';

// fetch all events
$events = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Events | Sameer Motorsport</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
  margin:0;
  font-family:Poppins,sans-serif;
  background:#050505;
  color:#fff;
}

/* dotted grid background */
body::before{
  content:"";
  position:fixed;
  inset:0;
  background-image:radial-gradient(#ffffff22 1px, transparent 1px);
  background-size:22px 22px;
  z-index:-1;
}

/* container */
.container{
  padding:120px 6% 60px;
}

h1{
  color:#ff3c00;
  margin-bottom:30px;
}

/* event grid */
.event-grid{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:30px;
}

/* event card */
.event-card{
  background:#1b1b1b;
  border-radius:16px;
  overflow:hidden;
  box-shadow:0 0 30px #000;
  transition:0.3s;
}

.event-card:hover{
  transform:translateY(-6px);
  box-shadow:0 0 25px #00c8ff44;
}

/* image */
.event-card img{
  width:100%;
  height:200px;
  object-fit:cover;
}

/* content */
.event-content{
  padding:20px;
}

.event-content h3{
  margin:0 0 10px;
  color:#ff3c00;
}

.event-meta{
  font-size:14px;
  opacity:0.85;
  margin-bottom:15px;
}

.event-meta span{
  display:block;
}

/* button */
.event-btn{
  display:inline-block;
  margin-top:10px;
  padding:10px 18px;
  background:#ff3c00;
  color:#fff;
  text-decoration:none;
  border-radius:8px;
  font-size:14px;
}

/* responsive */
@media (max-width: 900px){
  .event-grid{
    grid-template-columns:repeat(2,1fr);
  }
}

@media (max-width: 500px){
  .event-grid{
    grid-template-columns:1fr;
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


.event-slider{
  width:100%;
  aspect-ratio: 1 / 1; /* ALWAYS square */
  position:relative;
  overflow:hidden;
}



.event-slider img{
  position:absolute;
  inset:0;
  width:100%;
  height:100%;
  object-fit:cover;
  opacity:0;
  transition:opacity 1s ease-in-out;
}
.event-grid{
  justify-items:center;
}
.event-card{
  max-width:360px;
  width:100%;
}

.event-slider img.active{
  opacity:1;
}

@media (max-width: 500px){
  .container{
    padding:100px 5% 40px;
  }

  .event-card{
    max-width:360px;
    margin:0 auto;
  }
}
/* Mobile square image fix */
@media (max-width: 500px){
  .event-slider{
    height:auto;
    aspect-ratio:1 / 1; /* square */
  }

  .event-slider img{
    height:100%;
    width:100%;
  }
}
.event-grid{
  justify-items:center;
}


/* ================= MOBILE FIX ================= */
@media (max-width: 600px){

  .container{
    padding:90px 16px 40px;
  }

  .event-grid{
    grid-template-columns:1fr;
    justify-items:center;
  }

  .event-card{
    width:100%;
    max-width:360px;
    margin:0 auto;
  }

  /* Force square image on mobile */
  .event-slider{
    width:100%;
    aspect-ratio:1 / 1;
    height:auto;
  }

  .event-slider img{
    width:100%;
    height:100%;
    object-fit:cover;
  }

  .event-content{
    padding:18px;
    text-align:center;
  }

  .event-btn{
    width:100%;
    text-align:center;
  }
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
  <h1>Our Events</h1>

  <div class="event-grid">

  <?php while($event = mysqli_fetch_assoc($events)): ?>

    <?php
      // fetch first image of event
      $imgRes = mysqli_query(
        $conn,
        "SELECT image_name FROM event_images WHERE event_id=".$event['id']." LIMIT 1"
      );
      $img = mysqli_fetch_assoc($imgRes);
    ?>

    <div class="event-card">
      <div class="event-slider" data-index="0">
  <?php
    $imgs = mysqli_query(
      $conn,
      "SELECT image_name FROM event_images WHERE event_id=".$event['id']
    );
    while($img = mysqli_fetch_assoc($imgs)):
  ?>
    <img src="../uploads/event_images/<?= $img['image_name'] ?>">
  <?php endwhile; ?>
</div>


      <div class="event-content">
        <h3><?= $event['title'] ?></h3>

        <div class="event-meta">
          <span>📅 <?= date("d M Y", strtotime($event['event_date'])) ?></span>
          <span>💰 ₹<?= $event['registration_fee'] ?></span>
        </div>

        <a href="event_details.php?id=<?= $event['id'] ?>" class="event-btn">
          View Details
        </a>
      </div>
    </div>

  <?php endwhile; ?>

  </div>
  
</div>
<?php include '../includes/footer.php'; ?>
<script>
document.querySelectorAll('.event-slider').forEach(slider => {
  const images = slider.querySelectorAll('img');
  if(images.length === 0) return;

  let index = 0;
  images[0].classList.add('active');

  setInterval(() => {
    images[index].classList.remove('active');
    index = (index + 1) % images.length;
    images[index].classList.add('active');
  }, 2000); // change image every 3 seconds
});
function toggleMenu() {
  document.getElementById('navMenu').classList.toggle('active');
}
</script>


</body>
</html>
