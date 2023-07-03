<?php

include 'dbconnect.php';

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $pass = sha1($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM tbl_employer WHERE employer_email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $number_of_rows = $stmt->rowCount();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $rows = $stmt->fetchAll();
    if ($number_of_rows  > 0) {
        foreach ($rows as $user)
        {
            $employer_name = $user['employer_name'];
            $employer_phone = $user['employer_phone'];
        }
        session_start();
        $_SESSION["sessionid"] = session_id();
        $_SESSION["employer_email"] = $email;
        $_SESSION["employer_name"] = $employer_name;
        $_SESSION["employer_phone"] = $employer_phone;
        
        echo "<script>alert('Login Success');</script>";
        echo "<script> window.location.replace('index.php')</script>";
    }else{
         echo "<script>alert('Login Failed');</script>";
         echo "<script> window.location.replace('login.php')</script>";
    }
}



?>
<!DOCTYPE html>
<html>
    <head>    
        <title>Employee Management System</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Karma">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.png">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
        <style>
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
            .copy-right {
                background-color: black;
                padding: 22px;
                text-align: center;
                font-size:20px;
                margin-top:43px;
                color:white;
                border-top: 1px solid lightgrey;
            }
        </style>
    </head> 
<body>
    <div class="background-image"></div>
      <div class="w3-main w3-content w3-padding" style="max-width:600px;margin-top:120px;border-radius:5px;">
         <div class="w3-row w3-card" style="background: #f8f8f8; display: flex; justify-content: center; align-items: center;border-radius:10px;">
         
            <div class="w3-container" style="margin-top: 50px; margin-bottom:50px; ; width: 80%;">
               <h4 style="font-size: 40px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; margin-bottom:40px; text-align:center;">Login</h4>
               <form name="loginForm" class=""  action="login.php" method="post">
                  <p>
                     <label style="color: rgb(22, 36, 120);">
                     <b style="margin-top: 10px;">Email</b>
                     </label>
                     <input class="w3-input w3-border w3-round" name="email" type="email" id="idemail" required>
                  </p>
                  <p>
                     <label style="color: rgb(22, 36, 120);">
                      <b style="margin-top: 10px;">Password</b>
                     </label>
                     <div class="input-group">
                        <input class="w3-input w3-border w3-round" name="password" type="password" id="idpass" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('idpass', 'togglePassword')">
                        <i class="far fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                        </button>
                    </div>
                  </p>
                  <p style="display: flex; align-items: center; margin-top: 10px;">
                    <input class="w3-check" type="checkbox" id="idremember" name="remember" onclick="rememberMe()" style="margin-right: 10px;">
                    <label for="idremember" style="margin-right: 10px; display: flex;">Remember Me</label>
                    <span style="margin-left: auto;">
                      <a href="#" style="text-decoration: none;">Forgot password?</a>
                    </span>
                  </p>


                  <p>
                     <button class="button w3-btn w3-round w3-block" style="margin-top: 30px; background:rgb(21, 29, 79);" name="submit" value="login">Login</button>
                  </p>
               </form>

            </div>
         </div>
      </div>

      <script>
      function togglePasswordVisibility(inputId, eyeIconId) {
        var input = document.getElementById(inputId);
        var eyeIcon = document.getElementById(eyeIconId);

        if (input.type === 'password') {
          input.type = 'text';
          eyeIcon.classList.remove('fa-eye');
          eyeIcon.classList.add('fa-eye-slash');
        } else {
          input.type = 'password';
          eyeIcon.classList.remove('fa-eye-slash');
          eyeIcon.classList.add('fa-eye');
        }
      }
    </script>
      
      <footer> 
        <div class="copy-right">
            <p>
                Copyright © 2023 | Employee Management System®
            </p>
        </div>
    </footer>
      
   </body>
</html>