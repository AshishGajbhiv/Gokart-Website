<?php
session_start();
require '../includes/db.php';

// Security: only admin
if (!isset($_SESSION['admin_logged_in'])) {
    die("Unauthorized access");
}

// Set CSV headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=registrations.csv');

// Create output stream
$output = fopen('php://output', 'w');

// CSV column headers
fputcsv($output, [
    'Team Name',
    'Captain Name',
    'Vice Captain',
    'Email',
    'Mobile',
    'College',
    'Event',
    'Category',
    'Status',
    'Created At'
]);

// Fetch data
$query = "
SELECT 
    r.team_name,
    r.captain_name,
    r.vice_captain_name,
    r.email,
    r.mobile,
    r.college,
    e.title AS event_title,
    r.vehicle_category,
    r.status,
    r.created_at
FROM registrations r
LEFT JOIN events e ON r.event_id = e.id
ORDER BY r.created_at DESC
";

$result = mysqli_query($conn, $query);

// Write rows
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['team_name'],
        $row['captain_name'],
        $row['vice_captain_name'],
        $row['email'],
        $row['mobile'],
        $row['college'],
        $row['event_title'],
        $row['vehicle_category'],
        $row['status'],
        $row['created_at']
    ]);
}

fclose($output);
exit;
