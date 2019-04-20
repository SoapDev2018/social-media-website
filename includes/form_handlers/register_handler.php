<?php
  $fname = ""; //First Name
  $lname = ""; //Last Name
  $em = ""; //Email
  $em2 = ""; //Confirm email
  $password = ""; //Password
  $password2 = ""; //Confirm Password
  $date = ""; //Date
  $error_array = array(); //Errors during sign-up/login

  if(isset($_POST['register_btn'])) {
    //First Name
    $fname = strip_tags($_POST['reg_fname']); //Strips reg_fname of all HTML tags
    $fname = str_replace(' ','',$fname); //Replaces all occurence of ' ' with ''
    $fname = ucfirst(strtolower($fname)); //Converts to lowercase first and then first character to uppercase
    $_SESSION['reg_fname'] = $fname; //Stores first name into session variable

    //Last Name
    $lname = strip_tags($_POST['reg_lname']); //Strips reg_lname of all HTML tags
    $lname = str_replace(' ','',$lname); //Replaces all occurence of ' ' with ''
    $lname = ucfirst(strtolower($lname)); //Converts to lowercase first and then first character to uppercase
    $_SESSION['reg_lname'] = $lname; //Stores last name into session variable

    //Email
    $em = strip_tags($_POST['reg_email']); //Strips reg_email of all HTML tags
    $em = str_replace(' ','',$em); //Replaces all occurence of ' ' with ''
    $em = strtolower($em); //Converts to lowercase
    $_SESSION['reg_email'] = $em; //Stores email into session variable

    //Confirm email
    $em2 = strip_tags($_POST['reg_conf_email']); //Strips reg_conf_email of all HTML tags
    $em2 = str_replace(' ','',$em2); //Replaces all occurence of ' ' with ''
    $em2 = strtolower($em2); //Converts to lowercase
    $_SESSION['reg_conf_email'] = $em2; //Stores confirm email into session variable

    //Password
    $password = strip_tags($_POST['reg_password']);
    $password2 = strip_tags($_POST['reg_conf_password']);

    //Current Date
    $date_object = new DateTime();
    $date = $date_object->format('Y-m-d');

    if($em==$em2) {
      //Check if email is in valid format
      if(filter_var($em, FILTER_VALIDATE_EMAIL)) {
        $em = filter_var($em, FILTER_VALIDATE_EMAIL);
        $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");
        $num_rows = mysqli_num_rows($e_check);
        if($num_rows>0)
          array_push($error_array, "Email already exists!<br>");
      }else {
        array_push($error_array, "Invalid email format!<br>");
      }
    }else {
      array_push($error_array, "Emails dont match!<br>");
    }

    //Validate first name
    if(strlen($fname)>32 || strlen($fname)<2)
      array_push($error_array, "Your first name should be between 2 and 32 characters!<br>");

    //Validate last name
    if(strlen($lname)>32 || strlen($lname)<2)
      array_push($error_array, "Your last name should be between 2 and 32 characters!<br>");

    //Validate if password and confirm password are equal
    if($password != $password2)
      array_push($error_array, "Your passwords do not match!<br>");

    //Validate if password is of valid length
    if(strlen($password)>64 || strlen($password)<8)
      array_push($error_array, "Your password must be between 8 and 64 characters<br>");

    if(empty($error_array)) {
      $password = md5($password);
      
      //Generating username by concatenating first name and last name
      $last = "";
      $row = "";
      $username = strtolower($fname . "_" . $lname);
      $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
      $i = 0;
      while(mysqli_num_rows($check_username_query) != 0) {
        ++$i;
        $username = $username . "_" . $i;
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
        if(mysqli_num_rows($check_username_query) != 0) {
          $last = strrpos($username, "_");
          $last = substr($last, strlen($username)-1);
          $username = substr($username,0,$last-2);
          if(is_numeric($last))
            $i = (int)$last;
        }
      }

      //Profile picture assignment
      $rand = rand(1, 16);
      $profile_pic = "";
      switch($rand) {
        case 1:
          $profile_pic="./assets/images/profile_pics/defaults/1.png";
          break;
        case 2:
          $profile_pic="./assets/images/profile_pics/defaults/2.png";
          break;
        case 3:
          $profile_pic="./assets/images/profile_pics/defaults/3.png";
          break;
        case 4:
          $profile_pic="./assets/images/profile_pics/defaults/4.png";
          break;
        case 5:
          $profile_pic="./assets/images/profile_pics/defaults/5.png";
          break;
        case 6:
          $profile_pic="./assets/images/profile_pics/defaults/6.png";
          break;
        case 7:
          $profile_pic="./assets/images/profile_pics/defaults/7.png";
          break;
        case 8:
          $profile_pic="./assets/images/profile_pics/defaults/8.png";
          break;
        case 9:
          $profile_pic="./assets/images/profile_pics/defaults/9.png";
          break;
        case 10:
          $profile_pic="./assets/images/profile_pics/defaults/10.png";
          break;
        case 11:
          $profile_pic="./assets/images/profile_pics/defaults/11.png";
          break;
        case 12:
          $profile_pic="./assets/images/profile_pics/defaults/12.png";
          break;
        case 13:
          $profile_pic="./assets/images/profile_pics/defaults/13.png";
          break;
        case 14:
          $profile_pic="./assets/images/profile_pics/defaults/14.png";
          break;
        case 15:
          $profile_pic="./assets/images/profile_pics/defaults/15.png";
          break;
        case 16:
          $profile_pic="./assets/images/profile_pics/defaults/16.png";
          break;
        default:
          $profile_pic="./assets/images/profile_pics/defaults/1.png";  
      }

      $date_code = date("Y-m-d H:i:s");
      $date_code = md5($date_code);
      $code = md5(rand(0, 1000));
      $code = $code . $date_code;
      $hash = md5($code);

      $query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',', '$hash', 'no')");

      array_push($error_array, "<span style='color: #14C800;'>You're all set! Go ahead and verify your email with the link sent to your profile!</span><br>");

      //Sending email to registered email of user
      $to = $em;
      $subject = 'Signup | Verification | Project Olympia | Social Media Website'; // Give the email a subject 
      $message = '
      
      Thanks for signing up!
      Your account has been created, you can login with the credentials you have provided after you have activated your account by pressing the url below.

      Please click this link to activate your account:
      http://example.com/verify.php?email='.$em.'&hash='.$hash.'
      
      ';
      $headers = 'From:noreply@example.com' . "\r\n"; // Set from headers
      mail($to, $subject, $message, $headers); // Send our email

      //Clear session variables
      $_SESSION['reg_fname']="";
      $_SESSION['reg_lname']="";
      $_SESSION['reg_email']="";
      $_SESSION['reg_conf_email']="";
    }
  }
?>