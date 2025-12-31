<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Add notice
if (isset($_POST['add_notice'])) {

    $text = mysqli_real_escape_string($conn, $_POST['notice_text']);

    // Deactivate old notices
    mysqli_query($conn, "UPDATE notices SET is_active = 0");

    // Insert new notice
    mysqli_query(
        $conn,
        "INSERT INTO notices (notice_text, is_active) VALUES ('$text', 1)"
    );
}

// Delete notice
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM notices WHERE id=$id");
}

// Fetch all notices
$notices = mysqli_query(
    $conn,
    "SELECT * FROM notices ORDER BY created_at DESC"
);
?>
<!DOCTYPE html>
<html>
<head>
<title>Manage Notices</title>
<style>
body{background:#050505;color:#fff;font-family:Poppins;}
.container{padding:120px 6%;max-width:700px;margin:auto;}
form, .card{
  background:#1b1b1b;
  padding:20px;
  border-radius:14px;
  margin-bottom:20px;
}
textarea{width:100%;padding:12px;border-radius:8px;border:none;}
button{background:#ff3c00;color:#fff;padding:10px 16px;border:none;border-radius:8px;cursor:pointer;}
.delete{background:#d9534f;text-decoration:none;padding:6px 12px;border-radius:6px;color:#fff;font-size:12px;}
.active{color:#5cb85c;font-size:12px;}
</style>
</head>

<body>
<div class="container">

<h2>📢 Manage Notices</h2>

<form method="POST">
<textarea name="notice_text" required placeholder="Enter notice text"></textarea>
<br><br>
<button name="add_notice">Publish Notice</button>
</form>

<?php while($n = mysqli_fetch_assoc($notices)): ?>
<div class="card">
  <p><?= htmlspecialchars($n['notice_text']) ?></p>
  <?php if($n['is_active']): ?>
    <span class="active">ACTIVE</span>
  <?php endif; ?>
  <br><br>
  <a href="?delete=<?= $n['id'] ?>" class="delete"
     onclick="return confirm('Delete this notice?')">Delete</a>
</div>
<?php endwhile; ?>

</div>
</body>
</html>
