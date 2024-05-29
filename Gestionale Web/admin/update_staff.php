<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_POST['UpdateStaff'])) {
    if (empty($_POST["staff_number"]) || empty($_POST["staff_name"]) || empty($_POST['staff_email']) || empty($_POST['staff_password'])) {
        $err = "Blank Values Not Accepted";
    } else {
        $staff_number = $_POST['staff_number'];
        $staff_name = $_POST['staff_name'];
        $staff_email = $_POST['staff_email'];
        $staff_password = password_hash($_POST['staff_password'], PASSWORD_DEFAULT); 
        $update = $_POST['staff_id'];

        $postQuery = "UPDATE staff SET staff_number = ?, staff_name = ?, staff_email = ?, staff_password = ? WHERE staff_id = ?";
        $postStmt = $mysqli->prepare($postQuery);
        $rc = $postStmt->bind_param('ssssi', $staff_number, $staff_name, $staff_email, $staff_password, $update);
        $postStmt->execute();
        if ($postStmt) {
            $success = "Staff Updated";
            header("refresh:1; url=hrm.php");
        } else {
            $err = "Please Try Again Or Try Later";
        }
    }
}

require_once('partials/_head.php');
?>

<body>
    <?php require_once('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>
        <?php
        if (isset($_GET['update'])) {
            $update = $_GET['update'];
            $ret = "SELECT * FROM staff WHERE staff_id = ?";
            $stmt = $mysqli->prepare($ret);
            $stmt->bind_param('i', $update);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($staff = $res->fetch_object()) {
        ?>
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
                                    <h3>Please Fill All Fields</h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <input type="hidden" name="staff_id" value="<?php echo $staff->staff_id; ?>">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <label>Staff Number</label>
                                                <input type="text" name="staff_number" class="form-control" value="<?php echo $staff->staff_number; ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Staff Name</label>
                                                <input type="text" name="staff_name" class="form-control" value="<?php echo $staff->staff_name; ?>">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <label>Staff Email</label>
                                                <input type="email" name="staff_email" class="form-control" value="<?php echo $staff->staff_email; ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Staff Password</label>
                                                <input type="password" name="staff_password" class="form-control" value="">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <input type="submit" name="UpdateStaff" value="Update Staff" class="btn btn-success">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php require_once('partials/_footer.php'); ?>
                </div>
        <?php
            } else {
                echo "<div class='container-fluid mt--8'><div class='alert alert-danger'>Staff member not found.</div></div>";
            }
            $stmt->close();
        } else {
            echo "<div class='container-fluid mt--8'><div class='alert alert-danger'>No staff ID provided.</div></div>";
        }
        ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>

</html>

<?php
$mysqli->close();
?>
