<?php
  include("config/config.php");
  if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])) {
    $email = mysqli_escape_string($con, $_GET['email']);
    $hash = mysqli_escape_string($con, $_GET['hash']);
    $query = mysqli_query($con, "SELECT email, code, verified FROM users WHERE email='$email' AND code='$hash' AND verified='no'");
    $verified_query = mysqli_query($con, "SELECT email, code, verified FROM users WHERE email='$email'");
    $row = mysqli_fetch_array($verified_query);
    $count = mysqli_num_rows($query);
    if($count > 0) {
      $message = "Congratulations! Your account has been activated and you can login!";
      $update_act = mysqli_query($con, "UPDATE users SET code='0', verified='yes' WHERE email='$email'");
    }
    else {
      if($row['verified'] == "yes")
        $message = "You have already activated your account.";
      else {
        $message = "Your account could not be activated because of a wrong URL. A new activation mail has been sent to you!";
        $date = date("Y-m-d H:i:s");
        $date = md5($date);
        $code = md5(rand(0, 1000));
        $code = $code . $date;
        $hash = md5($code);

        $to = $email;
        $subject = 'Signup | Verification | Project Olympia | Social Media Website'; // Give the email a subject 
        $message_body = '
        
        Thanks for signing up!
        Your account has been created, you can login with the credentials you have provided after you have activated your account by pressing the url below.

        Please click this link to activate your account:
        http://example.com/verify.php?email='.$email.'&hash='.$hash.'
        
        ';
        $headers = 'From:noreply@example.com' . "\r\n"; // Set from headers
        mail($to, $subject, $message_body, $headers); // Send our email
        $hash_update_query = mysqli_query($con, "UPDATE users SET code='$hash' WHERE email='$email'");
      }
    }
  }
  else {
    $message = "Wrong approach to activate account!";
  }
?>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <title>Verification | Project Olympia | Social Media Website</title>
  </head>
  <body>
    <div class="statusmsg">
      <?php
        echo $message;
      ?>
      <br>
      <br>
      <br>
      <a href='register.php'>Click here to go to login page!</a>
    </div>
  </body>
</html>