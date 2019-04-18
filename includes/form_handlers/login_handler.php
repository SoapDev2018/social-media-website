<?php
  if(isset($_POST['login_btn'])) {
    $email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL);
    $_SESSION['log_email'] = $email;

    $password = md5($_POST['log_password']);
    $check_verified_query = mysqli_query($con, "SELECT verified FROM users WHERE email='$email'");
    $check_verified_query_row = mysqli_fetch_array($check_verified_query);
    $check_db_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    if($check_verified_query_row['verified'] == "yes") {
      if(mysqli_num_rows($check_db_query) == 1) {
        $row = mysqli_fetch_array($check_db_query);
        $username = $row['username'];

        $user_closed_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND user_closed='yes'");
        if(mysqli_num_rows($user_closed_query) == 1)
          $reopen_account=mysqli_query($con, "UPDATE users SET user_closed='no' WHERE email='$email'");

        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
      }else {
        array_push($error_array, "Email or password was incorrect<br>");
      }
    }
    else {
      array_push($error_array, "Your account was not activated! A new activation email has been sent to your email<br>");
      $date = date("Y-m-d H:i:s");
      $date = md5($date);
      $code = md5(rand(0, 1000));
      $code = $code . $date;
      $hash = md5($code);

      $to = $email;
      $subject = 'Signup | Verification | Project Olympia | Social Media Website'; // Give the email a subject 
      $message = '
      
      Thanks for signing up!
      Your account has been created, you can login with the credentials you provided after you have activated your account by pressing the url below.
      
      Please click this link to activate your account:
      http://example.com/verify.php?email='.$email.'&hash='.$hash.'
      
      ';
      $headers = 'From:noreply@example.com' . "\r\n"; // Set from headers
      mail($to, $subject, $message, $headers); // Send our email
      $hash_update_query = mysqli_query($con, "UPDATE users SET code='$hash' WHERE email='$email'");
    }
  }
?>