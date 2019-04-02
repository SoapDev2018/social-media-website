<?php 
include("includes/header.php");
if(isset($_GET['profile_username'])) {
    $username = $_GET['profile_username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
    $user_arrar = mysqli_fetch_array($user_details_query);
    $num_friends = (substr_count($user_arrar['friend_array'],","))-1;
}
?>
    <style>
        .wrapper {
            margin-left: 0px;
            padding-left: 0px;
        }
    </style>
    <div class="profile_left">
        <img src="<?php echo $user_arrar['profile_pic']; ?>">
        <div class="profile_info">
            <p><?php echo "Posts: " . $user_arrar['num_posts']; ?></p>
            <p><?php echo "Likes: " . $user_arrar['num_likes']; ?></p>
            <p><?php echo "Friends: " . $num_friends; ?></p>
        </div>
    </div>
    <div class="main_column column">
		  <?php echo $username; ?>
    </div>
	</div>
</body>
</html>