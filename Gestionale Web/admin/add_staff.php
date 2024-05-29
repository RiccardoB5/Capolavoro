<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();

if (isset($_POST['addStaff'])) {
  if (empty($_POST["staff_name"]) || empty($_POST["staff_number"]) || empty($_POST['staff_email']) || empty($_POST['staff_password'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $staff_name = $_POST['staff_name'];
    $staff_number = $_POST['staff_number'];
    $staff_email = $_POST['staff_email'];
    $staff_password = sha1(md5($_POST['staff_password'])); 

    $postQuery = "INSERT INTO staff (staff_name, staff_number, staff_email, staff_password) VALUES (?, ?, ?, ?)";
    $postStmt = $mysqli->prepare($postQuery);
    $rc = $postStmt->bind_param('ssss', $staff_name, $staff_number, $staff_email, $staff_password);
    $postStmt->execute();
    if ($postStmt) {
      $success = "Staff aggiunto con successo!" && header("refresh:1; url=staff.php");
    } else {
      $err = "Errore: lo staff non è stato aggiunto. Riprova più tardi.";
    }
  }
}

require_once('partials/_head.php');
?>

<body>
  <?php require_once('partials/_sidebar.php'); ?>
  <div class="main-content">
    <?php require_once('partials/_topnav.php'); ?>
    <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body"></div>
      </div>
    </div>
    <div class="container-fluid mt--8">
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3>Per favore compila tutti i campi</h3>
            </div>
            <div class="card-body">
              <?php
              if (isset($success)) {
                echo "<div class='alert alert-success'>$success</div>";
              }
              if (isset($err)) {
                echo "<div class='alert alert-danger'>$err</div>";
              }
              ?>
              <form method="POST">
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Nome Staff</label>
                    <input type="text" name="staff_name" class="form-control" value="" required>
                  </div>
                  <div class="col-md-6">
                    <label>Numero Staff</label>
                    <input type="text" name="staff_number" class="form-control" value="" required>
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Email Staff</label>
                    <input type="email" name="staff_email" class="form-control" value="" required>
                  </div>
                  <div class="col-md-6">
                    <label>Password Staff</label>
                    <input type="password" name="staff_password" class="form-control" value="" required>
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="addStaff" value="Add Staff" class="btn btn-success">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php require_once('partials/_footer.php'); ?>
    </div>
  </div>
  <?php require_once('partials/_scripts.php'); ?>
</body>

</html>
