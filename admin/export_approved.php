<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) exit('Unauthorized');
if (!isset($_GET['event_id'])) exit('Event not specified');

$event_id = (int)$_GET['event_id'];

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename=approved_event_'.$event_id.'.csv');

$output = fopen('php://output', 'w');

fputcsv($output, [
    'Team Name',
    'Captain Name',
    'Email',
    'Mobile',
    'College',
    'Category'
]);

$result = mysqli_query($conn, "
    SELECT team_name, captain_name, email, mobile, college, vehicle_category
    FROM registrations
    WHERE event_id=$event_id AND status='APPROVED'
    ORDER BY team_name ASC
");

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);
exit;
