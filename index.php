<?php
  include("includes/header.php");
  include("includes/classes/User.php");
  include("includes/classes/Post.php");

  if(isset($_POST['post'])) {
    $post = new Post($con, $userLoggedIn);
    $post->submitPost($_POST['post_text'], 'none');
  }
?>
    <div class="user_details column">
      <a href="<?php echo $userLoggedIn ?>"><img src="<?php echo $user['profile_pic']; ?>" alt="Profile Picture"></a>
      <div class="user_details_left_right">
        <a href="<?php echo $userLoggedIn ?>">
        <?php
          echo $user['first_name']." ".$user['last_name'];
        ?>
        </a>
        <br>
        <?php
          echo "Posts: "." ".$user['num_posts']."<br>";
          echo "Likes: "." ".$user['num_likes'];
        ?>
      </div>
    </div>

    <div class="main_column column">
      <form action="index.php" method="POST" class="post_form">
        <textarea name="post_text" id="post_text" placeholder="Got something to say?"></textarea>
        <input type="submit" name="post" id="post" value="Post">
        <hr>
      </form>
    </div>
  </div>
</body>
</html>