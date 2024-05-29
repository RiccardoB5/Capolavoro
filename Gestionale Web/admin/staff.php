<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $adn = "DELETE FROM staff WHERE staff_id = ?";
  $stmt = $mysqli->prepare($adn);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  if ($stmt->affected_rows > 0) {
    $success;
  } else {
    $err;
  }
  $stmt->close();
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
              <a href="add_staff.php" class="btn btn-outline-success"><i class="fas fa-user-plus"></i> Add New Staff</a>
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
              <div class="table-responsive">
                <table class="table align-items-center table-flush">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">Staff Number</th>
                      <th scope="col">Name</th>
                      <th scope="col">Email</th>
                      <th scope="col">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $ret = "SELECT * FROM staff";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    while ($staff = $res->fetch_object()) {
                    ?>
                      <tr>
                        <td><?php echo $staff->staff_number; ?></td>
                        <td><?php echo $staff->staff_name; ?></td>
                        <td><?php echo $staff->staff_email; ?></td>
                        <td>
                          <a href="staff.php?delete=<?php echo $staff->staff_id; ?>">
                            <button class="btn btn-sm btn-danger">
                              <i class="fas fa-trash"></i> Delete
                            </button>
                          </a>
                          <a href="update_staff.php?update=<?php echo $staff->staff_id; ?>">
                            <button class="btn btn-sm btn-primary">
                              <i class="fas fa-user-edit"></i> Update
                            </button>
                          </a>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
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
