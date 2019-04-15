<?php
  if(isset($_POST['update_details'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    $email_check = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
    $row = mysqli_fetch_array($email_check);
    $matched_user = $row['username'];

    if($matched_user == "" || $matched_user == $userLoggedIn) {
      $message = "Details updated!<br><br>";
      $query = mysqli_query($con, "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email' WHERE username='$userLoggedIn'");
    }
    else
      $message = "Email already in use!<br><br>";
  }
  else
    $message = "";

  
  if(isset($_POST['update_password'])) {
    $old_password = strip_tags($_POST['old_password']);
    $new_password = strip_tags($_POST['new_password']);
    $new_conf_password = strip_tags($_POST['new_conf_password']);

    $password_query = mysqli_query($con, "SELECT password FROM users WHERE username='$userLoggedIn'");
    $row = mysqli_fetch_array($password_query);
    $db_password = $row['password'];
    if(md5($old_password) == $db_password) {
      if($new_password == $new_conf_password) {
        if(strlen($new_password) < 4)
          $password_message = "Sorry, your new password does not match the minimum requirements!<br><br>";
        else {
          $new_password = md5($new_password);
          $password_query = mysqli_query($con, "UPDATE users SET password='$new_password' WHERE username='$userLoggedIn'");
          $password_message = "Password has been changed successfully!<br><br>";
        }
      }
      else
        $password_message = "Your two new passwords need to match!<br><br>";
    }
    else
      $password_message = "Your old password does not match with our records!<br><br>";
  }
  else
    $password_message = "";


  if(isset($_POST['close_account']))
    header("Location: close_account.php");
?>