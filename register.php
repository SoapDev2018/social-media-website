<?php
  require './config/config.php';
  require './includes/form_handlers/register_handler.php';
  require './includes/form_handlers/login_handler.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="./assets/css/register_style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="./assets/js/register.js"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Register | Project Olympia</title>
</head>
<body>
  <?php
    if(isset($_POST['register_btn'])) {
      echo '
        <script>
          $(document).ready(function () {
            $(".first").hide();
            $(".second").show();
          });
        </script>
      ';
    }
  ?>
  <div class="wrapper">
    <div class="login_box">
        <div class="login_header">
          <h1>Olympia</h1>
          Login or SignUp below!
        </div>
        <div class="first">
          <!-- Login Form -->
          <form action="./register.php" method="POST">
            <input type="email" name="log_email" id="log_email" placeholder="Email" value="<?php 
            if(isset($_SESSION['log_email'])) echo $_SESSION['log_email'];
            ?>" required>
            <br>
            <input type="password" name="log_password" id="log_password" placeholder="Password">
            <br>
            <?php if(in_array("Email or password was incorrect<br>", $error_array))
                echo "Email or password was incorrect<br>"; ?>
            <input type="submit" name="login_btn" value="Login!">
            <br>
            <a href="#" id="signup" class="signup">Need an account? Register Here</a>
          </form>
        </div>

        <div class="second">
          <!-- Register Form -->
          <form action="./register.php" method="POST">
            <input type="text" name="reg_fname" id="reg_fname" placeholder="First Name" value="<?php 
            if(isset($_SESSION['reg_fname'])) echo $_SESSION['reg_fname'];
            ?>" required>
            <br>
            <?php if(in_array("Your first name should be between 2 and 32 characters!<br>",$error_array))
                    echo "Your first name should be between 2 and 32 characters!<br>"; ?>
            <input type="text" name="reg_lname" id="reg_lname" placeholder="Last Name" value="<?php 
            if(isset($_SESSION['reg_lname'])) echo $_SESSION['reg_lname'];
            ?>" required>
            <br>
            <?php if(in_array("Your last name should be between 2 and 32 characters!<br>",$error_array))
                    echo "Your last name should be between 2 and 32 characters!<br>"; ?>
            <input type="email" name="reg_email" id="reg_email" placeholder="Email" value="<?php 
            if(isset($_SESSION['reg_email'])) echo $_SESSION['reg_email'];
            ?>" required>
            <br>
            <input type="email" name="reg_conf_email" id="reg_conf_email" placeholder="Confirm email" value="<?php 
            if(isset($_SESSION['reg_conf_email'])) echo $_SESSION['reg_conf_email'];
            ?>" required>
            <br>
            <?php if(in_array("Email already exists!<br>",$error_array))
                    echo "Email already exists!<br>";
                  else if(in_array("Invalid email format!<br>",$error_array))
                    echo "Invalid email format!<br>";
                  else if(in_array("Emails dont match!<br>",$error_array))
                    echo "Emails dont match!<br>"; ?>
            <input type="password" name="reg_password" id="reg_password" placeholder="Password" required>
            <br>
            <?php if(in_array("Your passwords do not match!<br>",$error_array))
                    echo "Your passwords do not match!<br>";
                  else if(in_array("Your password must be between 8 and 64 characters<br>",$error_array))
                    echo "Your password must be between 8 and 64 characters<br>"; ?>
            <input type="password" name="reg_conf_password" id="reg_conf_password" placeholder="Confirm password" required>
            <br>
            <?php if(in_array("Your passwords do not match!<br>",$error_array))
                    echo "Your passwords do not match!<br>";
                  else if(in_array("Your password must be between 8 and 64 characters<br>",$error_array))
                    echo "Your password must be between 8 and 64 characters<br>"; ?>
            <input type="submit" name="register_btn" value="Register">
            <br>
            <?php if(in_array("<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>",$error_array))
                    echo "<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>"; ?>
            <a href="#" id="login" class="login">Already have an account? Signin here</a>
          </form>
        </div>
    </div>
  </div>
</body>
</html>