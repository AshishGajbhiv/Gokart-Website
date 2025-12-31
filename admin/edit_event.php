<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_events.php");
    exit;
}

$event_id = (int) $_GET['id'];

/* ================= FETCH EVENT ================= */
$eventResult = mysqli_query($conn, "SELECT * FROM events WHERE id=$event_id");
$event = mysqli_fetch_assoc($eventResult);

if (!$event) {
    exit("Event not found");
}

/* ================= FETCH IMAGES ================= */
$images = mysqli_query(
    $conn,
    "SELECT image_name FROM event_images WHERE event_id=$event_id"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Event | Admin</title>
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
.container{
  padding:120px 6%;
  max-width:820px;
  margin:auto;
}
h2{
  color:#ff3c00;
  margin-bottom:20px;
}
form{
  background:#1b1b1b;
  padding:30px;
  border-radius:16px;
  box-shadow:0 0 30px #000;
}
label{
  display:block;
  margin-bottom:6px;
  font-size:14px;
}
input, textarea, select{
  width:100%;
  margin-bottom:18px;
  padding:10px;
  border:none;
  border-radius:6px;
}
textarea{
  resize:vertical;
  min-height:120px;
}
img{
  width:110px;
  border-radius:8px;
  margin:6px 10px 10px 0;
}
button{
  background:#ff3c00;
  color:#fff;
  border:none;
  padding:12px 24px;
  border-radius:10px;
  cursor:pointer;
}
.back-btn{
  display:inline-block;
  margin-bottom:20px;
  background:#333;
  padding:8px 14px;
  border-radius:8px;
  color:#fff;
  text-decoration:none;
  font-size:14px;
}
.section{
  margin-top:30px;
  padding-top:20px;
  border-top:1px solid #333;
}
.section h3{
  color:#ff3c00;
  font-size:16px;
  margin-bottom:15px;
}
.status-badge{
  display:inline-block;
  padding:6px 12px;
  border-radius:20px;
  font-size:12px;
  margin-bottom:15px;
}
.OPEN{ background:#5cb85c; color:#000; }
.CLOSED{ background:#d9534f; color:#fff; }
</style>
</head>

<body>

<div class="container">

<a href="manage_events.php" class="back-btn">← Back to Events</a>

<h2>Edit Event</h2>

<span class="status-badge <?= $event['registration_status'] ?>">
  Registration: <?= htmlspecialchars($event['registration_status']) ?>
</span>

<form action="update_event.php" method="POST" enctype="multipart/form-data">

<input type="hidden" name="event_id" value="<?= $event_id ?>">

<label>Event Title</label>
<input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" required>

<label>Description</label>
<textarea name="description" required><?= htmlspecialchars($event['description']) ?></textarea>

<label>Event Date</label>
<input type="date" name="event_date" value="<?= $event['event_date'] ?>" required>

<label>Registration Status</label>
<select name="registration_status" required>
  <option value="OPEN" <?= $event['registration_status']=='OPEN'?'selected':'' ?>>Open</option>
  <option value="CLOSED" <?= $event['registration_status']=='CLOSED'?'selected':'' ?>>Closed</option>
</select>

<label>Registration Fee</label>
<input type="number" name="registration_fee" value="<?= $event['registration_fee'] ?>" required>

<div class="section">
<h3>Event Images</h3>

<?php while($img=mysqli_fetch_assoc($images)): ?>
  <img src="../uploads/event_images/<?= htmlspecialchars($img['image_name']) ?>">
<?php endwhile; ?>

<label>Replace Images (optional)</label>
<input type="file" name="event_images[]" multiple accept="image/*">
</div>

<div class="section">
<h3>Rulebook</h3>
<input type="file" name="rulebook_pdf" accept=".pdf">
</div>

<div class="section">
<h3>Payment QR Code</h3>

<?php if (!empty($event['qr_code'])): ?>
  <img src="../uploads/event_qr/<?= htmlspecialchars($event['qr_code']) ?>" style="width:150px;">
<?php endif; ?>

<input type="file" name="qr_code" accept="image/*">
</div>

<div class="section">
<h3>Bank Details</h3>

<label>Bank Name</label>
<input type="text" name="bank_name" value="<?= htmlspecialchars($event['bank_name'] ?? '') ?>">

<label>Account Holder Name</label>
<input type="text" name="account_holder" value="<?= htmlspecialchars($event['account_holder'] ?? '') ?>">

<label>Account Number</label>
<input type="text" name="account_number" value="<?= htmlspecialchars($event['account_number'] ?? '') ?>">

<label>IFSC Code</label>
<input type="text" name="ifsc_code" value="<?= htmlspecialchars($event['ifsc_code'] ?? '') ?>">
</div>

<button type="submit">Update Event</button>

</form>
</div>

</body>
</html>
