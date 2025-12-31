<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Fetch events with registration counts
$query = "
SELECT 
    e.id,
    e.title,
    COUNT(r.id) AS total_regs,
    SUM(r.status='APPROVED') AS approved_regs,
    SUM(r.status='PENDING') AS pending_regs
FROM events e
LEFT JOIN registrations r ON e.id = r.event_id
GROUP BY e.id
ORDER BY e.created_at DESC
";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
<title>Event Registrations | Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

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
.container{
  padding:120px 8%;
}
h2{
  color:#ff3c00;
  margin-bottom:30px;
}
.cards{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:25px;
}
.card{
  background:#1b1b1b;
  padding:25px;
  border-radius:16px;
  box-shadow:0 0 30px #000;
}
.card h3{
  color:#ff3c00;
  margin-bottom:12px;
}
.card p{
  font-size:14px;
  margin-bottom:6px;
  opacity:0.85;
}
.card a{
  display:inline-block;
  margin-top:15px;
  padding:10px 16px;
  background:#ff3c00;
  color:#fff;
  text-decoration:none;
  border-radius:8px;
  font-size:14px;
}
.admin-nav{
  position:fixed;
  top:0;
  left:0;
  width:100%;
  padding:16px 8%;
  background:rgba(0,0,0,0.85);
  z-index:1000;
}
.admin-nav a{
  background:#ff3c00;
  padding:8px 14px;
  border-radius:8px;
  color:#fff;
  text-decoration:none;
  margin-right:10px;
}
</style>
</head>

<body>

<div class="admin-nav">
  <a href="dashboard.php">← Dashboard</a>
</div>

<div class="container">
<h2>Select Event</h2>

<div class="cards">
<?php while($row = mysqli_fetch_assoc($result)): ?>
  <div class="card">
    <h3><?= htmlspecialchars($row['title']) ?></h3>
    <p>Total Registrations: <?= $row['total_regs'] ?></p>
    <p>Approved: <?= $row['approved_regs'] ?? 0 ?></p>
    <p>Pending: <?= $row['pending_regs'] ?? 0 ?></p>
    <a href="registrations.php?event_id=<?= $row['id'] ?>">
      View Registrations
    </a>
  </div>
<?php endwhile; ?>
</div>

</div>
</body>
</html>
