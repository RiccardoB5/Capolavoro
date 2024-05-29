<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_POST['addUser'])) {
  if (empty($_POST["user_name"]) || empty($_POST['user_password'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $Username = $_POST['user_name'];
    $Password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
    $query = "INSERT INTO utenti (Username, Password) VALUES(?, ?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ss', $Username, $Password);
    $stmt->execute();
    if ($stmt) {
      $success = "User Added" && header("refresh:1; url=customes.php"); 
    } else {
      $err = "Please Try Again Or Try Later";
    }
  }
}
require_once('partials/_head.php');
?>

<body>
  <?php
  require_once('partials/_sidebar.php');
  ?>
  <div class="main-content">
    <?php
    require_once('partials/_topnav.php');
    ?>
    <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;"
      class="header pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body">
        </div>
      </div>
    </div>
    <div class="container-fluid mt--8">
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3>Please Fill All Fields</h3>
            <div class="card-body">
              <form method="POST">
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Username</label>
                    <input type="text" name="user_name" class="form-control">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Password</label>
                    <input type="password" name="user_password" class="form-control">
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="addUser" value="Add User" class="btn btn-success">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php
      require_once('partials/_footer.php');
      ?>
    </div>
  </div>
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>

</html>
