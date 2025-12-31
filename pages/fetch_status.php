<?php
require '../includes/db.php';

if (!isset($_POST['email'])) {
    exit;
}

$email = mysqli_real_escape_string($conn, $_POST['email']);

$result = mysqli_query($conn, "
    SELECT r.status, r.rejection_reason, e.title
    FROM registrations r
    LEFT JOIN events e ON r.event_id = e.id
    WHERE r.email = '$email'
    ORDER BY r.created_at DESC
");

if (mysqli_num_rows($result) === 0) {
    echo "<p style='margin-top:20px;color:#f0ad4e;'>No registrations found for this email.</p>";
    exit;
}

while ($row = mysqli_fetch_assoc($result)) {

    $status = $row['status'];
    $class  = strtolower($status);

    echo "<div class='status-card'>";
    echo "<h3>Event: {$row['title']}</h3>";

    if ($status === 'APPROVED') {
        echo "<p class='approved'>✅ APPROVED</p>";
    }
    elseif ($status === 'PENDING') {
        echo "<p class='pending'>⏳ PENDING</p>";
    }
    else {
        echo "<p class='rejected'>❌ REJECTED</p>";
        if (!empty($row['rejection_reason'])) {
            echo "<p class='reason'><b>Reason:</b> {$row['rejection_reason']}</p>";
        }
    }

    echo "</div>";
}
