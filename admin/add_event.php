<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $fee = $_POST['registration_fee'];
    $reg_status = $_POST['registration_status'];


    // Upload rulebook
    $rulebookName = time() . '_' . $_FILES['rulebook_pdf']['name'];
    move_uploaded_file(
        $_FILES['rulebook_pdf']['tmp_name'],
        "../uploads/event_rulebook/" . $rulebookName
    );

    // Insert event
    $query = "INSERT INTO events (title, description, event_date, registration_fee, rulebook_pdf, registration_status)
              VALUES ('$title','$description','$event_date','$fee','$rulebookName', '$reg_status')";
    mysqli_query($conn, $query);

    $event_id = mysqli_insert_id($conn);

    // Upload event images (max 5)
    foreach ($_FILES['event_images']['tmp_name'] as $key => $tmpName) {
        if ($key >= 5) break;

        $imgName = time() . '_' . $_FILES['event_images']['name'][$key];
        move_uploaded_file($tmpName, "../uploads/event_images/" . $imgName);

        mysqli_query(
            $conn,
            "INSERT INTO event_images (event_id, image_name)
             VALUES ($event_id, '$imgName')"
        );
    }

    echo "<script>alert('Event added successfully');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Event</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{
            background:#050505;
            color:#fff;
            font-family:Poppins,sans-serif;
            padding:40px;
        }
        form{
            max-width:600px;
            margin:auto;
            background:#1b1b1b;
            padding:30px;
            border-radius:14px;
        }
        input, textarea{
            width:100%;
            margin-bottom:15px;
            padding:10px;
            border:none;
            border-radius:6px;
        }
        button{
            background:#ff3c00;
            border:none;
            color:#fff;
            padding:12px;
            width:100%;
            border-radius:8px;
            font-size:16px;
        }
        .nav-btn{
  color: #fff;
  background: #ff3c00;
  padding: 8px 16px;
  border-radius: 8px;
  text-decoration: none;
  font-size: 14px;
  margin-right: 30px;
}

    </style>
</head>

<body>
<nav class="admin-nav">
    <a href="dashboard.php" class="nav-btn">← Dashboard</a>
    <a href="/gokart/index.php" class="nav-btn">Home</a>
    <a href="/gokart/pages/events.php" class="nav-btn">Events</a>
</nav>
<h2>Add Event</h2>

<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Event Title" required>
    <textarea name="description" placeholder="Event Description" required></textarea>
    <input type="date" name="event_date" required>
    <input type="number" name="registration_fee" placeholder="Registration Fee" required>

    <label>Event Images (Max 5)</label>
    <input type="file" name="event_images[]" multiple required>

    <label>Rulebook PDF</label>
    <input type="file" name="rulebook_pdf" accept=".pdf" required>

    <button type="submit">Add Event</button>


    <label>Registration Status</label>
<select name="registration_status" required>
  <option value="OPEN">Open</option>
  <option value="CLOSED">Closed</option>
</select>

</form>

</body>
</html>
