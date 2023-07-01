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
            $user_name = $user['user_name'];
            $user_phone = $user['user_phone'];
        }
        session_start();
        $_SESSION["sessionid"] = session_id();
        $_SESSION["user_email"] = $email;
        $_SESSION["user_name"] = $user_name;
        $_SESSION["user_phone"] = $user_phone;
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
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
        <style>
            .copy-right {
                background-color: transparent;
                padding: 22px;
                text-align: center;
                font-size:20px;
                margin-top:20px;
                color:black;
                border-top: 1px solid lightgrey;
            }
        </style>
    </head> 
<body>
      <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:50px">
         <div class="w3-row w3-card">
         <div class="w3-half w3-container" style="margin-top: 50px; margin-bottom: 50px; text-align: center;">
          <img class="w3-image w3-center w3-padding" style="width:100%; height:100%;object-fit:cover;" src="images/login.png">
        </div>
            <div class="w3-half w3-container" style="margin-top: 50px; margin-bottom:50px;">
               <h4 style="font-size: 40px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; margin-bottom:40px;">Login</h4>
               <form name="loginForm" class=""  action="login.php" method="post">
                  <p>
                     <label style="color: #004891;">
                     <b style="margin-top: 10px;">Email</b>
                     </label>
                     <input class="w3-input w3-border w3-round" name="email" type="email" id="idemail" required>
                  </p>
                  <p>
                     <label style="color: #004891;">
                     <b style="margin-top: 10px;">Password</b>
                     </label>
                     <div class="input-group">
                        <input class="w3-input w3-border w3-round" name="password" type="password" id="idpass" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('idpass', 'togglePassword')">
                        <i class="far fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                        </button>
                    </div>
                  </p>
                  <p>
                     <input class="w3-check" style="margin-top: 10px;" type="checkbox" id="idremember" name="remember" onclick="rememberMe()">
                     <label>Remember Me</label>
                  </p>
                  <p>
                     <button class="button w3-btn w3-round w3-block" style="margin-top: 30px;" name="submit" value="login">Login</button>
                  </p>
               </form>

               <p style="margin-top: 30px; font-size:medium;">Dont have an account.  <a href="#" style="text-decoration:none;"> Create here.</a><br>
               Forgot your password?  <a href="#" style="text-decoration:none;" onclick="document.getElementById('id01').style.display='block';return false;"> Click here.</a>
               </p>

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