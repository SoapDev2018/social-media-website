<?php
  ob_start();
  session_start();

  $timezone = date_default_timezone_set("Asia/Kolkata");

  $con = mysqli_connect("localhost", "root", "", "olympia"); //Connection Variable

  if(mysqli_connect_errno())
    echo 'Failed to connect to database'.mysqli_connect_errno();
?>