<?php
  require 'config/config.php';
  include("includes/classes/User.php");
  include("includes/classes/Post.php");
  include("includes/classes/Message.php");
  include("includes/classes/Notification.php");

  if(isset($_SESSION['username'])) {
    $userLoggedIn = $_SESSION['username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
    $user = mysqli_fetch_assoc($user_details_query);
  }
  else
    header("Location: register.php");
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="assets/css/jquery.Jcrop.css">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.8.1/css/all.css">
  <link rel="stylesheet" href="https://static.fontawesome.com/css/fontawesome-app.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/bootbox.all.min.js"></script>
  <script src="assets/js/olympia.js"></script>
  <script src="assets/js/jcrop_bits.js"></script>
  <script src="assets/js/jquery.Jcrop.js"></script>

  <title>Project Olympia | Social Media Website</title>
</head>
<body>
  <div class="top_bar">
    <div class="logo">
      <a href="index.php">Olympia</a>
    </div>

    <div class="search">
      <form action="search.php" method="GET" name="search_form">
        <input type="text" onkeyup="getLiveSearchUsers(this.value, '<?php echo $userLoggedIn; ?>')" name="q" placeholder="Search..." autocomplete="off" id="search_text_input">
        <div class="button_holder">
          <img src="assets/images/icons/magnifying.png" alt="Search Icon">
        </div>
      </form>
      <div class="search_results"></div>
      <div class="search_results_footer_empty"></div>
    </div>

    <nav>
      <?php
        //Unread messages
        $messages = new Message($con, $userLoggedIn);
        $num_messages = $messages->getUnreadNumber();

        //Unread notifications
        $notifications = new Notification($con, $userLoggedIn);
        $num_notifications = $notifications->getUnreadNumber();

        //Unread friend requests
        $user_obj = new User($con, $userLoggedIn);
        $num_friendRequests = $user_obj->getNumberOfFriendRequests();
      ?>
      <a href="<?php echo $userLoggedIn ?>">
        <?php echo $user['first_name']; ?>
      </a>
      <a href="index.php"><i class="fas fa-home fa-lg"></i></a>
      <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>','message')"><i class="fas fa-envelope fa-lg"></i>
        <?php
          if($num_messages > 0)
          echo '<span class="notification_badge" id="unread_message">' . $num_messages . '</span>';
        ?>
      </a>
      <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>','notification')"><i class="fas fa-bell fa-lg"></i>
        <?php
          if($num_notifications > 0)
          echo '<span class="notification_badge" id="unread_notification">' . $num_notifications . '</span>';
        ?>
      </a>
      <a href="requests.php"><i class="fas fa-users fa-lg"></i>
        <?php
          if($num_friendRequests > 0)
          echo '<span class="notification_badge" id="unread_requests">' . $num_friendRequests . '</span>';
        ?>
      </a>
      <a href="settings.php"><i class="fas fa-cog fa-lg"></i></a>
      <a href="includes/handlers/logout.php"><i class="fas fa-sign-out-alt fa-lg"></i></a>
    </nav>
    <div class="dropdown_data_window" style="height: 0px; border: none;"></div>
    <input type="hidden" id="dropdown_data_type" value="">
  </div>
  <script>
    var userLoggedIn = '<?php echo $userLoggedIn; ?>';
    $(document).ready(function() {
      $('.dropdown_data_window').scroll(function() {
        var inner_height = $('.dropdown_data_window').innerHeight();
        var scroll_top = $('.dropdown_data_window').scrollTop();
        var page = $('.dropdown_data_window').find('.nextPageDropdownData');
        var noMoreData = $('.dropdown_data_window').find('.noMoreDropdownData').val();

        if((scroll_top + inner_height >= $('.dropdown_data_window')[0].scrollHeight) && noMoreData == 'false') {
          var pageName; //Holds name of page to send ajax request to
          var type = $('#dropdown_data_type').val();
          if(type == 'notification')
            pageName = "ajax_load_notifications.php";
          else if(type == 'message')
            pageName = "ajax_load_messages.php";

          var ajaxReq = $.ajax({
            url: "includes/handlers/" + pageName,
            type: "POST",
            data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
            cache: false,

            success: function(respone) {
              $('.dropdown_data_window').find('.nextPageDropdownData').remove();
              $('.dropdown_data_window').find('.noMoreDropdownData').remove();
              $('.dropdown_data_window').append(response);
            }
          });
        }
        return false;
      });
    });
  </script>
  <div class="wrapper">