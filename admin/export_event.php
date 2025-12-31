<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    exit('Unauthorized access');
}

if (!isset($_GET['event_id'])) {
    exit('Event not specified');
}

$event_id = (int) $_GET['event_id'];

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=event_'.$event_id.'_registrations.csv');

$output = fopen('php://output', 'w');

/* CSV HEADERS */
fputcsv($output, [
    'Team Name',
    'Captain Name',
    'Email',
    'Mobile',
    'College',
    'Category',
    'Status',
    'Rejection Reason'
]);

$query = mysqli_query($conn, "
    SELECT 
        team_name,
        captain_name,
        email,
        mobile,
        college,
        vehicle_category,
        status,
        rejection_reason
    FROM registrations
    WHERE event_id = $event_id
    ORDER BY team_name ASC
");

while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, [
        $row['team_name'],
        $row['captain_name'],
        $row['email'],
        $row['mobile'],
        $row['college'],
        $row['vehicle_category'],
        $row['status'],
        $row['rejection_reason']
    ]);
}

fclose($output);
exit;
