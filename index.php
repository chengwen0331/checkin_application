<?php

session_start();

// Check if the user is not logged in and redirect to the login page
include 'dbconnect.php';

if (isset($_SESSION['employer_email'])) {
    $email = $_SESSION['employer_email'];
    // Rest of the code for retrieving employer details

//$email = "ch3ngw3n0331@gmail.com";
$stmt = $conn->prepare("SELECT * FROM tbl_employer WHERE employer_email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();
$employer = $stmt->fetch(PDO::FETCH_ASSOC);
}
// Retrieve total employee count
$stmt = $conn->query("SELECT COUNT(*) AS total_employee FROM tbl_employee");
$total_employee = $stmt->fetch(PDO::FETCH_ASSOC)['total_employee'];

// Retrieve the latest check-in date
$stmt = $conn->query("SELECT MAX(checkin_date) AS latest_checkin_date FROM tbl_checkin");
$latest_checkin_date = $stmt->fetch(PDO::FETCH_ASSOC)['latest_checkin_date'];

// Retrieve the total number of employees with the same and latest check-in date
$stmt = $conn->query("SELECT COUNT(*) AS total_employee_latest_checkin FROM tbl_checkin WHERE checkin_date = (SELECT MAX(checkin_date) FROM tbl_checkin)");
$total_employee_latest_checkin = $stmt->fetch(PDO::FETCH_ASSOC)['total_employee_latest_checkin'];

if (isset($_GET['submit']) && $_GET['submit'] == "search") {
    $search = $_GET['search'];

    // Prepare the SQL query to search for matching records in tbl_employee
    $stmt = $conn->prepare("SELECT e.employee_id, e.employee_name, e.employee_department, IF(c.employee_id IS NULL, 'Absent', 'Present') AS status, c.checkin_date, c.checkin_time
                            FROM tbl_employee e
                            LEFT JOIN tbl_checkin c ON e.employee_id = c.employee_id
                            WHERE e.employee_name LIKE :search
                            ORDER BY c.checkin_date DESC");
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} 
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['selectedDepartment'])) {
                $selectedDepartment = $_POST['selectedDepartment'];

                // Prepare the SQL query to search for matching records in tbl_employee based on the selected department            
                if ($selectedDepartment === "All") {
                    // Retrieve all employee data
                    $stmt = $conn->query("SELECT e.employee_id, e.employee_name, e.employee_department, IF(c.employee_id IS NULL, 'Absent', 'Present') AS status, c.checkin_date, c.checkin_time
                                        FROM tbl_employee e
                                        LEFT JOIN tbl_checkin c ON e.employee_id = c.employee_id
                                        ORDER BY c.checkin_date DESC");
                    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } 
                else {
                    // Prepare the SQL query to search for matching records in tbl_employee based on the selected department
                    $stmt = $conn->prepare("SELECT e.employee_id, e.employee_name, e.employee_department, IF(c.employee_id IS NULL, 'Absent', 'Present') AS status, c.checkin_date, c.checkin_time
                                            FROM tbl_employee e
                                            LEFT JOIN tbl_checkin c ON e.employee_id = c.employee_id
                                            WHERE e.employee_department = :department 
                                            ORDER BY c.checkin_date DESC");
                    $stmt->bindValue(':department', $selectedDepartment, PDO::PARAM_STR);
                    $stmt->execute();
                    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                if (count($employees) === 0) {
                    // No records found for the selected department
                    $noRecordsFound = true;
                }
                
            }

        }

else {
    // The search form is not submitted, retrieve all records from tbl_employee
    $stmt = $conn->query("SELECT e.employee_id, e.employee_name, e.employee_department, IF(c.employee_id IS NULL, 'Absent', 'Present') AS status, c.checkin_date, c.checkin_time
                          FROM tbl_employee e
                          LEFT JOIN tbl_checkin c ON e.employee_id = c.employee_id
                          ORDER BY c.checkin_date DESC");
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkin System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Add your CSS stylesheets and other head elements here -->
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            list-style: none;
            text-decoration: none;
        }
        .content {
            width: 100%;
            height: 100vh;        
        }
        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("images/picture.png");
            background-size: cover;
            background-position: center;
            z-index: -1;
            }

            .background-image::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Adjust the transparency and color as needed */
            }
        .container-1{
            background: white;
            
        }
        .container-2{
            padding:30px;
        }

        .row{
            margin-top:18px;
        }
        
        .container-2 .box {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            padding: 10px;
            text-align: center;
        }

        .container-2 .box + .box {
            margin-left: 40px; /* Adjust the spacing as desired */
        }

        @media (min-width: 576px) {
            .container-2 .col-12 {
                display: flex;
                justify-content: center;
            }

            .container-2 .box {
                flex-basis: 30%;
            }
        }

        .profile-box{
            background:grey;
        }
        .profile-container {
            position: relative;
            margin-right:15px;
        }

        .profile-header {
            text-align: right;
            padding: 10px;
        }

        .profile-header i {
            font-size: 30px;
        }

        .profile-button {
            background: none;
            border: none;
            outline: none;
            cursor: pointer;
        }

        .profile-details {
            position: absolute;
            top: 0;
            right: -300px; /* Initially hide the profile details to the right */
            height: auto;
            width: 400px;
            background-color: #fff;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            padding: 10px;
            z-index: 999;
            transition: right 0.3s ease-in-out;
            display:none;
            flex-direction: column;
            align-items: center;
            text-align: justify;

        }

        .profile-details.active {
            right: 0; /* Display the profile details to the right */
            display:block;
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
            transition: color 0.3s ease-in-out;
            font-size: 30px;
        }

        .cancel-button:hover {
            color: #555;
        }

        .profile-img {
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            margin-bottom:10px;
        }

        .profile-img img {
            width: 90%;
            height: 90%;
            object-fit: cover;
        }

        .profile-details p{
            padding:10px;
            margin:8px;
        }

        .logout-button {
            background: none;
            border: none;
            outline: none;
            cursor: pointer;
            color:black;
        }

        .col-8 h3 {
            margin-bottom: 10px;
        }

        .table {
            border: 1px solid #ccc;
            border-collapse: collapse;
        }

        .table th, .table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        .table th {
            background-color: #f0f0f0;
            font-size:18px;
        }

        .table td{
            font-size:15px;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tr:hover {
            background-color: #e0e0e0;
        }
       
        .table .no-records {
            font-style: italic;
            color: black;
            text-align: center;
            padding: 16px;
            font-size:20px;
        }
        .icons-container {
            display: flex;
            align-items: center;
        }
        .filter-button{
            background: none;
            border: none;
            outline: none;
            cursor: pointer;
            font-size: 32px;
            margin-right: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
        .search-button{
            height:30px;
            display: flex;
            align-items: center;
            background: none;           
            outline: none;
            cursor: pointer;
            font-size: 25px;
            padding: 10px 10px;
            border: 2px solid black;
            border-radius: 20px;
            margin-right: 10px;
            
        }
        .search-button:hover input{
            width:300px;
        }
        .search-button input{
            width:0;
            outline: none;
            border:none;
            transition: 0.8s;
            background: transparent;    
            margin-right: 5px;   
            font-size: 16px;
        }
        .search-button input::placeholder{
            color:black;
            font-size: 16px;
            padding-left: 5px;
        }
        .filter-button + .search-button {
            margin-left: 22px; /* Adjust the spacing as desired */
        }
        .view-button {
            display: inline-block;
            padding: 6px 25px;
            background: rgb(91,106,199);
            border-radius: 5px;
            text-decoration: none;
            color: white;
        }
        .view-button:hover {
            background: rgb(46,53,102);
        }
        .dropdown {
            position: relative;
            display: inline-block;
            }

            .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            left: -160px; /* Added to position it to the left of the icon */
            top: 0;
            }

            .sub-dropdown {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            left: -245px;
            top: 0;
            }

            .sub-dropdown a{
            cursor: pointer;
            }
            .sub-dropdown a:hover{
            background:#ddd;
            }

            .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid #ddd;
            }

            .dropdown-content:hover .sub-dropdown {
            display: block;
            }

            .dropdown:hover .dropdown-content {
            display: block;
            }
            .option1{
            cursor: pointer;
            }
            .option1:hover{
            background:#ddd;
            }
            .option2{
            cursor: pointer;
            }
            .option2:hover{
            background:#ddd;
            }
    </style>
</head>
<body>
    <div class="content">
        <div class="container-1">
            <div class="profile-box">
                <div class="profile-container">
                    <div class="profile-header">
                        <button class="profile-button" onclick="toggleProfile()"><i class="fa-solid fa-circle-user"></i></button>
                        <a href="logout.php" style="margin-left: 10px;">
                            <i class="fa-solid fa-right-from-bracket logout-button"></i>
                        </a>

                    </div>
                </div>
            </div>
                    <div class="profile-details">
                        <button class="cancel-button" onclick="toggleProfile()"><i class="fa-sharp fa-solid fa-circle-xmark"></i></button><br><br>
                        <div class="profile-img">
                            <img src="images/profile.png">
                        </div>
                        <p><strong>Name:</strong> <?php echo $employer['employer_name']; ?></p>
                        <p><strong>Email:</strong> <?php echo $employer['employer_email']; ?></p>
                        <p><strong>Phone:</strong> <?php echo $employer['employer_phone']; ?></p>
                    </div>
                </div>
            
            <div class="container-2">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="box">
                            <h4><i class="fa-solid fa-users"></i><span class="icon-text">Total Employees<br><br> <?php echo $total_employee; ?></h4>
                        </div>
                        <div class="box">
                            <h4><i class="fa-sharp fa-solid fa-file-pen"></i><span class="icon-text">Attendance (<?php echo $latest_checkin_date; ?>)<br><br><?php echo $total_employee_latest_checkin; ?> / <?php echo $total_employee; ?></h4>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col">
                    <div class="col d-flex align-items-center justify-content-between">
                        <h3>Employee Information</h3>
                        <div class="ml-auto icons-container">
                        <form id="filterForm" method="post" action="index.php">
                            <div class="dropdown">
                                <button onclick="toggleDropdown()" class="filter-button">
                                    <ion-icon name="filter-circle"></ion-icon>
                                </button>
                                <div id="dropdownContent" class="dropdown-content">
                                    <a href="#" class="option1" onclick="selectOption('Department'); toggleDepartmentDropdown();">Department</a>
                                    <div id="departmentDropdown" class="sub-dropdown">
                                    <a href="#" name= "AllHuman Resources" onclick="selectDepartment('All')">All</a>
                                    <a href="#" name= "Human Resources" onclick="selectDepartment('Human Resources')">Human Resources</a>
                                    <a href="#" name= "Finance and Accounting" onclick="selectDepartment('Finance and Accounting')">Finance and Accounting</a>
                                    <a href="#" name= "Sales and Marketing" onclick="selectDepartment('Sales and Marketing')">Sales and Marketing</a>
                                    <a href="#" name= "Information Technology" onclick="selectDepartment('Information Technology')">Information Technology</a>
                                    <a href="#" name= "Customer Service" onclick="selectDepartment('Customer Service')">Customer Service</a>
                                    <a href="#" name= "Research and Development" onclick="selectDepartment('Research and Development')">Research and Development</a>
                                    <a href="#" name= "Production and Manufacturing" onclick="selectDepartment('Production and Manufacturing')">Production and Manufacturing</a>
                                    <a href="#" name= "Supply Chain and Logistics" onclick="selectDepartment('Supply Chain and Logistics')">Supply Chain and Logistics</a>
                                    <a href="#" name= "Administration and Support" onclick="selectDepartment('Administration and Support')">Administration and Support</a>
                                </div>
                                </div>      
                            </div>
                            <input type="hidden" id="selectedOption" name="selectedOption">
                            <input type="hidden" id="selectedDepartment" name="selectedDepartment">
                        </form>
                            <form method="get" action="index.php">
                                <div class="search-button">
                                    <input type="text" name="search" placeholder="SEARCH..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <input type="submit" name="submit" value="search" style="display: none;">
                                </div>
                            </form>
                        </div>
                    </div>

                        <?php if (isset($employees) && count($employees) > 0): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Check in Date</th>
                                        <th>Check in Time</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($employees as $employee): ?>
                                        <tr>
                                            <td><?php echo $employee['employee_name']; ?></td>
                                            <td><?php echo $employee['employee_department']; ?></td>
                                            <td><?php echo $employee['status']; ?></td>
                                            <td><?php echo $employee['checkin_date']; ?></td>
                                            <td><?php echo $employee['checkin_time']; ?></td>
                                            <td><a href="detail.php?employee_id=<?php echo $employee['employee_id']; ?>" class="view-button">View</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php elseif (isset($noRecordsFound) && $noRecordsFound): ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Department</th>
                                            <th>Status</th>
                                            <th>Check in Date</th>
                                            <th>Check in Time</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="no-records">No records found for the selected department.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Department</th>
                                            <th>Status</th>
                                            <th>Check in Date</th>
                                            <th>Check in Time</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="no-records">No records found.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                    </div>
                </div>             
            </div>
    </div>

    <!-- Add your JavaScript scripts and other body elements here -->
    <script>
        function toggleProfile() {
    const profileDetails = document.querySelector('.profile-details');
    profileDetails.classList.toggle('active');
}
    function toggleDropdown() {
        var dropdownContent = document.getElementById("dropdownContent");
        if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
        } else {
            dropdownContent.style.display = "block";
        }
    }

    function toggleDepartmentDropdown() {
  var departmentDropdown = document.getElementById("departmentDropdown");
  if (departmentDropdown.style.display === "block") {
    departmentDropdown.style.display = "none";
  } else {
    departmentDropdown.style.display = "block";
  }
}
function selectOption(option) {
  var selectedOptionInput = document.getElementById("selectedOption");
  selectedOptionInput.value = option;
}
function selectDepartment(department) {
  var selectedDepartmentInput = document.getElementById("selectedDepartment");
  selectedDepartmentInput.value = department;
  document.getElementById("filterForm").submit();
}


document.addEventListener('click', function(e) {
  var target = e.target;
  var dropdownContent = document.getElementById("dropdownContent");
  var departmentDropdown = document.getElementById("departmentDropdown");
  
  if (target !== dropdownContent && !dropdownContent.contains(target)) {
    dropdownContent.style.display = "none";
    departmentDropdown.style.display = "none";
  }
  
  if (target === departmentDropdown || target.parentNode === departmentDropdown || departmentDropdown.contains(target)) {
    toggleDepartmentDropdown();
  } else {
    departmentDropdown.style.display = "none";
  }

});
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>

