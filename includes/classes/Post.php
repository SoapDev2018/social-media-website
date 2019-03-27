<?php
  class Post {
    private $user_obj;
    private $con;

    public function __construct($con, $user) {
      $this->con = $con;
      $this->user_obj = new User($con, $user);
    }

    public function submitPost($body, $user_to) {
      $body = strip_tags($body);
      $body = mysqli_real_escape_string($this->con,$body);
      $check_empty = preg_replace('/\s+/','',$body);
      $date_object = new DateTime();
      if($check_empty != "") {
        $date_added = $date_object->format('Y-m-d H:i:s'); //Current date and time
        $added_by = $this->user_obj->getUsername(); //Get username
        
        //If user posts on own profile, user_to is none
        if($user_to == $added_by)
          $user_to = "none";
        $query = mysqli_query($this->con, "INSERT INTO posts VALUES('','$body','$added_by','$user_to','$date_added','no','no','0')");
        $returned_id = mysqli_insert_id($this->con);

        //Insert Notification

        //Update post count for user
        $num_posts = $this->user_obj->getNumPosts();
        $num_posts++;
        $update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
      }
    }

    public function loadPostsFriends() {
      $str = "";
      $data = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");
      while($row = mysqli_fetch_array($data)) {
        $id = $row['id'];
        $body = $row['body'];
        $added_by = $row['added_by'];
        $date_time = $row['date_added'];

        //Prepare user_to string so that it can even be included if not posted to user
        if($row['user_to'] == "none") {
          $user_to = "";
        }else {
          $user_to_obj = new User($con, $row['user_to']);
          $user_to_name = $user_to_obj->getFirstAndLastName();
          $user_to = "to <a href='" . $row['user_to'] . "'>" . $user_to_name . "</a>";
        }

        //Check if user who posted has their account closed
        $added_by_obj = new User($this->con, $added_by);
        if($added_by_obj->isClosed())
          continue;
        $user_details_query = mysqli_query($this->con, "SELECT first_name,last_name,profile_pic FROM users WHERE username='$added_by'");
        $user_row = mysqli_fetch_array($user_details_query);
        $first_name = $user_row['first_name'];
        $last_name = $user_row['last_name'];
        $profile_pic = $user_row['profile_pic'];

        //Getting timeframe
        $date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
					$end_date = new DateTime($date_time_now); //Current time
					$interval = $start_date->diff($end_date); //Difference between dates 
					if($interval->y >= 1) {
						if($interval == 1)
							$time_message = $interval->y . " year ago"; //1 year ago
						else 
							$time_message = $interval->y . " years ago"; //1+ year ago
					}
					else if ($interval-> m >= 1) {
						if($interval->d == 0) {
							$days = " ago";
						}
						else if($interval->d == 1) {
							$days = $interval->d . " day ago";
						}
						else {
							$days = $interval->d . " days ago";
						}


						if($interval->m == 1) {
							$time_message = $interval->m . " month". $days;
						}
						else {
							$time_message = $interval->m . " months". $days;
						}
					}
				  else if($interval->d >= 1) {
						if($interval->d == 1) {
							$time_message = "Yesterday";
						}
						else {
							$time_message = $interval->d . " days ago";
						}
					}
					else if($interval->h >= 1) {
						if($interval->h == 1) {
							$time_message = $interval->h . " hour ago";
						}
						else {
							$time_message = $interval->h . " hours ago";
						}
					}
					else if($interval->i >= 1) {
						if($interval->i == 1) {
							$time_message = $interval->i . " minute ago";
						}
						else {
							$time_message = $interval->i . " minutes ago";
						}
					}
					else {
						if($interval->s < 30) {
							$time_message = "Just now";
						}
						else {
							$time_message = $interval->s . " seconds ago";
						}
          }
          
        $str .= "<div class='status_post'>
                  <div class='post_profile_pic'>
                    <img src='$profile_pic' width='50'>
                  </div>
                  <div class='posted_by' style='color:#ACACAC;'>
                    <a href='$added_by'> $first_name $last_name </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;$time_message
                  </div>
                  <div id='post_body'>
                    $body<br>
                  </div>
                </div>";
      }

      echo $str;
    }
  }
?>