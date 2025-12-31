<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['event_id'])) {
    header("Location: manage_events.php");
    exit;
}

$event_id = intval($_POST['event_id']);

$title       = mysqli_real_escape_string($conn, $_POST['title']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$event_date  = $_POST['event_date'];
$fee         = $_POST['registration_fee'];
$reg_status  = $_POST['registration_status'];

/* ===============================
   BANK DETAILS (ALWAYS UPDATE)
   =============================== */
$bank_name       = mysqli_real_escape_string($conn, $_POST['bank_name']);
$account_holder  = mysqli_real_escape_string($conn, $_POST['account_holder']);
$account_number  = mysqli_real_escape_string($conn, $_POST['account_number']);
$ifsc_code       = mysqli_real_escape_string($conn, $_POST['ifsc_code']);

/* ===============================
   UPDATE MAIN EVENT DATA
   =============================== */
mysqli_query($conn, "
UPDATE events SET 
  title='$title',
  description='$description',
  event_date='$event_date',
  registration_fee='$fee',
  registration_status='$reg_status',
  bank_name='$bank_name',
  account_holder='$account_holder',
  account_number='$account_number',
  ifsc_code='$ifsc_code'
WHERE id=$event_id
");

/* ===============================
   RULEBOOK REPLACE (OPTIONAL)
   =============================== */
if (!empty($_FILES['rulebook_pdf']['name'])) {

    $old = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT rulebook_pdf FROM events WHERE id=$event_id")
    );

    if (!empty($old['rulebook_pdf']) && file_exists("../uploads/event_rulebook/".$old['rulebook_pdf'])) {
        unlink("../uploads/event_rulebook/".$old['rulebook_pdf']);
    }

    $newPdf = time().'_'.basename($_FILES['rulebook_pdf']['name']);
    move_uploaded_file(
        $_FILES['rulebook_pdf']['tmp_name'],
        "../uploads/event_rulebook/".$newPdf
    );

    mysqli_query($conn, "UPDATE events SET rulebook_pdf='$newPdf' WHERE id=$event_id");
}

/* ===============================
   QR CODE REPLACE (OPTIONAL)
   =============================== */
if (!empty($_FILES['qr_code']['name'])) {

    $oldQR = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT qr_code FROM events WHERE id=$event_id")
    );

    if (!empty($oldQR['qr_code']) && file_exists("../uploads/event_qr/".$oldQR['qr_code'])) {
        unlink("../uploads/event_qr/".$oldQR['qr_code']);
    }

    $qrName = time().'_'.basename($_FILES['qr_code']['name']);
    move_uploaded_file(
        $_FILES['qr_code']['tmp_name'],
        "../uploads/event_qr/".$qrName
    );

    mysqli_query($conn, "UPDATE events SET qr_code='$qrName' WHERE id=$event_id");
}

/* ===============================
   EVENT IMAGES REPLACE (OPTIONAL)
   =============================== */
if (!empty($_FILES['event_images']['name'][0])) {

    $imgs = mysqli_query(
        $conn,
        "SELECT image_name FROM event_images WHERE event_id=$event_id"
    );

    while ($img = mysqli_fetch_assoc($imgs)) {
        $path = "../uploads/event_images/".$img['image_name'];
        if (file_exists($path)) unlink($path);
    }

    mysqli_query($conn, "DELETE FROM event_images WHERE event_id=$event_id");

    foreach ($_FILES['event_images']['tmp_name'] as $key => $tmp) {
        if ($key >= 5) break;

        if (!empty($tmp)) {
            $imgName = time().'_'.basename($_FILES['event_images']['name'][$key]);
            move_uploaded_file($tmp, "../uploads/event_images/".$imgName);

            mysqli_query(
                $conn,
                "INSERT INTO event_images (event_id, image_name)
                 VALUES ($event_id, '$imgName')"
            );
        }
    }
}

header("Location: manage_events.php");
exit;
