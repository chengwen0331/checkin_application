<?php
// Assuming you have already established a database connection
session_start();
include 'dbconnect.php';
// Check if the employee_id parameter is provided in the URL
if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];

    // Retrieve employee details based on the employee_id
    $stmt = $conn->prepare("SELECT e.employee_name, e.employee_email, e.employee_phone, e.employee_department, c.checkin_lat, c.checkin_long, c.checkin_state, c.checkin_locality, c.checkin_date, c.checkin_time
                           FROM tbl_employee e
                           LEFT JOIN tbl_checkin c ON e.employee_id = c.employee_id
                           WHERE e.employee_id = :employee_id");
    $stmt->bindParam(":employee_id", $employee_id);
    $stmt->execute();
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if employee details are found
    if ($employee) {
        $employee_name = $employee['employee_name'];
        $employee_email = $employee['employee_email'];
        $employee_phone = $employee['employee_phone'];
        $employee_department = $employee['employee_department'];
        $checkin_lat = $employee['checkin_lat'];
        $checkin_long = $employee['checkin_long'];
        $checkin_state = $employee['checkin_state'];
        $checkin_locality = $employee['checkin_locality'];
        $checkin_date = $employee['checkin_date'];
        $checkin_time = $employee['checkin_time'];
    } else {
        // Employee not found, handle the error or redirect to an error page
        // For simplicity, we'll redirect back to index.php
        header("Location: index.php");
        exit;
    }
} else {
    // employee_id parameter is not provided, handle the error or redirect to an error page
    // For simplicity, we'll redirect back to index.php
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px;
            margin-top:20px;
        }

        .container .left {
            flex: 1.5;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .container .right {
            flex: 2;
        }

        .employee-image {
            width: 400px;
            height: 400px;
            margin-bottom: 20px;
        }

        .details-label {
            font-weight: bold;
            margin-top: 15px;
        }

        .details-label i{
            font-size: 18px;
            margin-right: 5px;
        }

        .cancel-button {
            background: none;
            border: none;
            outline: none;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
            color: black;
            font-size: 30px;
            margin-bottom: 20px;
        }

        .cancel-button:hover {
            color: #555;
        }
    </style>
</head>
<body>
        <a href="index.php" class="cancel-link">
            <button class="cancel-button"><i class="fa-sharp fa-solid fa-circle-xmark"></i></button>
        </a><br>
    <div class="container">
        <a href="index.php" class="cancel-link">
            <button class="cancel-button"><i class="fa-sharp fa-solid fa-circle-xmark"></i></button>
        </a>
        <div class="left">
            <img class="employee-image" src="images/profile.png" alt="Employee Image">
        </div>
        <div class="right">
                 
            <div class="details-label">
                <i class="fa-solid fa-address-card"></i>
                <label>
                     <b style="margin-top: 10px; font-size: 18px; font-weight:600;">Name</b>
                <input type="text" class="w3-input w3-border w3-round" style="font-weight:400; font-size: 15px; width: 70%; padding-left: 20px;" value="<?php echo $employee_name; ?>" readonly>
            </div>
            <div class="details-label">
                <i class="fa-solid fa-envelope"></i>
                <label>
                     <b style="margin-top: 10px; font-size: 18px; font-weight:600;">Email</b>
                <input type="text" class="w3-input w3-border w3-round" style="font-weight:400; font-size: 15px; width: 70%; padding-left: 20px;" value="<?php echo $employee_email; ?>" readonly>
            </div>
            <div class="details-label">
                <i class="fa-solid fa-phone"></i>
                <label>
                     <b style="margin-top: 10px; font-size: 18px; font-weight:600;">Phone Number</b>
                <input type="text" class="w3-input w3-border w3-round" style="font-weight:400; font-size: 15px; width: 70%; padding-left: 20px;" value="<?php echo $employee_phone; ?>" readonly>
            </div>
            <div class="details-label">
                <i class="fa-solid fa-user-gear"></i>
                <label>
                     <b style="margin-top: 10px; font-size: 18px; font-weight:600;">Department</b>
                <input type="text" class="w3-input w3-border w3-round" style="font-weight:400; font-size: 15px; width: 70%; padding-left: 20px;" value="<?php echo $employee_department; ?>" readonly>
            </div>
            <div class="details-label">
                <i class="fa-solid fa-location-crosshairs"></i>
                <label>
                     <b style="margin-top: 10px; font-size: 18px; font-weight:600;">Current Latitude</b>
                <input type="text" class="w3-input w3-border w3-round" style="font-weight:400; font-size: 15px; width: 70%; padding-left: 20px;" value="<?php echo $checkin_lat; ?>" readonly>
            </div>
            <div class="details-label">
                <i class="fa-sharp fa-solid fa-location-dot"></i>
                <label>
                     <b style="margin-top: 10px; font-size: 18px; font-weight:600;">Current Longitude</b>
                <input type="text" class="w3-input w3-border w3-round" style="font-weight:400; font-size: 15px; width: 70%; padding-left: 20px;" value="<?php echo $checkin_long; ?>" readonly>
            </div>
            <div class="details-label">
                <i class="fa-solid fa-flag"></i>
                <label>
                     <b style="margin-top: 10px; font-size: 18px; font-weight:600;">Current State</b>
                <input type="text" class="w3-input w3-border w3-round" style="font-weight:400; font-size: 15px; width: 70%; padding-left: 20px;" value="<?php echo $checkin_state; ?>" readonly>
            </div>
            <div class="details-label">
                <i class="fa-sharp fa-solid fa-location-arrow"></i>
                <label>
                     <b style="margin-top: 10px; font-size: 18px; font-weight:600;">Current Locality</b>
                <input type="text" class="w3-input w3-border w3-round" style="font-weight:400; font-size: 15px; width: 70%; padding-left: 20px;" value="<?php echo $checkin_locality; ?>" readonly>
            </div>
            <div class="details-label">
                <i class="fa-regular fa-calendar-days"></i>
                <label>
                     <b style="margin-top: 10px; font-size: 18px; font-weight:600;">Check-in Date</b>
                <input type="text" class="w3-input w3-border w3-round" style="font-weight:400; font-size: 15px; width: 70%; padding-left: 20px;" value="<?php echo $checkin_date; ?>" readonly>
            </div>
            <div class="details-label">
                <i class="fa-solid fa-clock"></i>
                <label>
                     <b style="margin-top: 10px; font-size: 18px; font-weight:600;">Check-in Time</b>
                <input type="text" class="w3-input w3-border w3-round" style="font-weight:400; font-size: 15px; width: 70%; padding-left: 20px;" value="<?php echo $checkin_time; ?>" readonly>
            </div>
        </div>
    </div>
</body>
</html>
