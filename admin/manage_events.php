<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$events = mysqli_query($conn, "SELECT * FROM events ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Manage Events</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{
  background:#050505;
  color:#fff;
  font-family:Poppins,sans-serif;
  padding: 2rem;
}
.container{
  padding:120px 6%;
}
table{
  width:100%;
  border-collapse:collapse;
  background:#1b1b1b;
  border-radius:12px;
  overflow:hidden;
}
th,td{
  padding:12px;
  text-align:left;
}
th{
  background:#111;
}
.action-btn{
  padding:6px 12px;
  border-radius:6px;
  color:#fff;
  text-decoration:none;
  font-size:13px;
}

    .nav-btn{
  color: #fff;
  background: #ff3c00;
  padding: 8px 16px;
  border-radius: 8px;
  text-decoration: none;
  font-size: 14px;
}
.edit{ background:#5bc0de; }
.delete{ background:#d9534f; }
</style>
</head>

<body>
<nav class="admin-nav">
    <a href="dashboard.php" class="nav-btn">← Dashboard</a>
    <a href="/gokart/index.php" class="nav-btn">Home</a>
    <a href="/gokart/pages/events.php" class="nav-btn">Events</a>
</nav>
<div class="container">
<h2>Manage Events</h2>

<table>
<tr>
  <th>Title</th>
  <th>Date</th>
  <th>Fee</th>
  <th>Action</th>
</tr>

<?php while($event = mysqli_fetch_assoc($events)): ?>
<tr>
  <td><?= $event['title'] ?></td>
  <td><?= $event['event_date'] ?></td>
  <td>₹<?= $event['registration_fee'] ?></td>
  <td>
    <a href="edit_event.php?id=<?= $event['id'] ?>" class="action-btn edit">Edit</a>
    <a href="delete_event.php?id=<?= $event['id'] ?>" class="action-btn delete"
       onclick="return confirm('Delete this event?')">Delete</a>
  </td>
</tr>
<?php endwhile; ?>

</table>
</div>

</body>
</html>
