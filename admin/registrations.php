<?php
session_start();
require '../includes/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../includes/PHPMailer/src/Exception.php';
require '../includes/PHPMailer/src/PHPMailer.php';
require '../includes/PHPMailer/src/SMTP.php';
require '../includes/config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

/* ================= EVENT FILTER ================= */

$event_id = $_GET['event_id'] ?? null;
$where = '';

if ($event_id) {
    $event_id = (int)$event_id;
    $where = "WHERE r.event_id = $event_id";

    

}



/* ================= EMAIL FUNCTIONS ================= */

function sendApprovalEmail($to, $team) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
    $mail->Username   = SMTP_EMAIL;
    $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('help.sandd@gmail.com', 'S & D Motorsports');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = 'Registration Approved | S & D Motorsports';
        $mail->Body = "
            <h2>Registration Approved 🎉</h2>
            <p>Dear Team <b>$team</b>,</p>
            <p>Your registration has been <b style='color:green;'>APPROVED</b>.</p>
            <p>Payment verified successfully.</p>
            <br>
            <b>S & D Motorsports Team</b>
        ";
        $mail->send();
    } catch (Exception $e) {}
}

function sendRejectionEmail($to, $team, $reason) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'help.sandd@gmail.com';
        $mail->Password = 'zeegfpxpvjxjhpbi';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('help.sandd@gmail.com', 'S & D Motorsports');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = 'Registration Rejected | S & D Motorsports';
        $mail->Body = "
            <h2>Registration Rejected</h2>
            <p>Dear Team <b>$team</b>,</p>
            <p>Your registration has been <b style='color:red;'>REJECTED</b>.</p>
            <p><b>Reason:</b> $reason</p>
            <p>You may correct the issue and register again.</p>
            <br>
            <b>S & D Motorsports Team</b>
        ";
        $mail->send();
    } catch (Exception $e) {}
}

/* ================= APPROVE ================= */

if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'approve') {
    $id = (int)$_GET['id'];

    mysqli_query($conn, "UPDATE registrations SET status='APPROVED' WHERE id=$id");

    $res = mysqli_query($conn, "SELECT email, team_name FROM registrations WHERE id=$id");
    $row = mysqli_fetch_assoc($res);

    sendApprovalEmail($row['email'], $row['team_name']);
}

/* ================= REJECT ================= */

if (isset($_POST['reject_id'], $_POST['reason'])) {
    $id = (int)$_POST['reject_id'];
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);

    mysqli_query($conn, "
        UPDATE registrations 
        SET status='REJECTED', rejection_reason='$reason'
        WHERE id=$id
    ");

    $res = mysqli_query($conn, "SELECT email, team_name FROM registrations WHERE id=$id");
    $row = mysqli_fetch_assoc($res);

    sendRejectionEmail($row['email'], $row['team_name'], $reason);
}

/* ================= FETCH REGISTRATIONS ================= */

$result = mysqli_query($conn, "
    SELECT r.*, e.title AS event_title
    FROM registrations r
    LEFT JOIN events e ON r.event_id = e.id
    $where
    ORDER BY r.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Registrations</title>

<style>
body{background:#050505;color:#fff;font-family:Poppins,sans-serif}
.container{padding:120px 6%}
h2{color:#ff3c00;margin-bottom:20px}

.admin-nav{position:fixed;top:0;left:0;width:100%;padding:16px 6%;
background:#000;display:flex;gap:14px;z-index:1000}
.nav-btn{background:#ff3c00;color:#fff;padding:8px 16px;border-radius:8px;text-decoration:none}

table{width:100%;border-collapse:collapse;background:#1b1b1b;border-radius:16px;overflow:hidden}
th,td{padding:12px;font-size:14px;text-align:left}
th{background:#111}

.status{padding:6px 10px;border-radius:20px;font-size:12px}
.PENDING{background:#f0ad4e;color:#000}
.APPROVED{background:#5cb85c;color:#000}
.REJECTED{background:#d9534f}

.action-btn{padding:6px 10px;border-radius:6px;font-size:12px;text-decoration:none}
.approve{background:#5cb85c;color:#000}
.reject{background:#d9534f;color:#fff}

.payment-thumb{width:60px;cursor:pointer;border-radius:6px}

/* IMAGE MODAL */
.img-modal{display:none;position:fixed;inset:0;background:#000c;z-index:2000;overflow-y:auto}
.img-modal img{display:block;margin:80px auto;max-width:95%}
.close{position:absolute;top:20px;right:30px;font-size:40px;cursor:pointer}

/* MOBILE */
@media(max-width:768px){
table,thead,tbody,tr,td{display:block;width:100%}
thead{display:none}
tr{margin-bottom:20px;background:#1b1b1b;padding:18px;border-radius:16px}
td::before{content:attr(data-label);display:block;color:#ff3c00;font-weight:600;margin-bottom:6px}
}
</style>
</head>

<body>

<nav class="admin-nav">
  <a href="dashboard.php" class="nav-btn">← Dashboard</a>
  <a href="/gokart/index.php" class="nav-btn">Home</a>
  <a href="/gokart/pages/events.php" class="nav-btn">Events</a>
</nav>

<div class="container">
<h2>Registration Requests</h2>

<?php if ($event_id): ?>
<div style="margin-bottom:20px;display:flex;gap:12px;flex-wrap:wrap;">
  <a href="export_event.php?event_id=<?= $event_id ?>" class="nav-btn">📤 Download All</a>
  <a href="export_approved.php?event_id=<?= $event_id ?>" class="nav-btn" style="background:#5cb85c">✅ Approved</a>
  <a href="export_rejected.php?event_id=<?= $event_id ?>" class="nav-btn" style="background:#d9534f">❌ Rejected</a>
</a>

</div>

<?php endif; ?>

<table>
<thead>
<tr>
<th>Team</th><th>Event</th><th>Email</th><th>Category</th>
<th>Logo</th><th>Payment</th><th>Status</th><th>Action</th>
</tr>
</thead>

<tbody>
<?php while($row=mysqli_fetch_assoc($result)): ?>
<tr>
<td data-label="Team"><?= $row['team_name'] ?></td>
<td data-label="Event"><?= $row['event_title'] ?></td>
<td data-label="Email"><?= $row['email'] ?></td>
<td data-label="Category"><?= $row['vehicle_category'] ?></td>

<td data-label="Logo">
<img src="../uploads/<?= $row['team_logo'] ?>" width="60">
</td>

<td data-label="Payment">
<img src="../uploads/<?= $row['payment_screenshot'] ?>" class="payment-thumb"
onclick="openImage(this.src)">
</td>

<td data-label="Status">
<span class="status <?= $row['status'] ?>"><?= $row['status'] ?></span>
</td>

<td data-label="Action">
<?php if($row['status']==='PENDING'): ?>
<a href="?action=approve&id=<?= $row['id'] ?>" class="action-btn approve">Approve</a>

<form method="POST" style="margin-top:8px">
<input type="hidden" name="reject_id" value="<?= $row['id'] ?>">
<select name="reason" required>
<option value="">Reason</option>
<option>Payment not received</option>
<option>Blur screenshot</option>
<option>Invalid proof</option>
<option>Incomplete details</option>
</select>
<button class="action-btn reject">Reject</button>
</form>
<?php else: ?> — <?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

<div id="imgModal" class="img-modal">
<span class="close" onclick="closeImage()">×</span>
<img id="modalImg">
</div>

<script>
function openImage(src){
  document.getElementById('imgModal').style.display='block';
  document.getElementById('modalImg').src=src;
}
function closeImage(){
  document.getElementById('imgModal').style.display='none';
}
</script>

</body>
</html>
