<header class="mb-auto">
    <div>
      <h3 class="float-md-start mb-0">Social<span class="text-info">NET</span>work</h3>
      <nav class="nav nav-masthead justify-content-center float-md-end">
          <?php if(isset($_SESSION["username"])) {?>
          <a class="nav-link nav-custom" href="index.php">Home</a>
          <a class="nav-link nav-custom" href="profile.php">Profile</a>
          <a class="nav-link nav-custom" href="followers.php">Connections</a>
          <a class="nav-link nav-custom" href="reset_password.php">Password change</a>
          <a class="nav-link nav-custom" href="logout.php">Logout</a>
          <?php }else { ?>
          <a class="nav-link nav-custom" href="index.php">Home</a>
          <a class="nav-link nav-custom" href="register.php">Register</a>
          <a class="nav-link nav-custom" href="login.php">Login</a>
          <?php } ?>
      </nav>
    </div>
  </header>