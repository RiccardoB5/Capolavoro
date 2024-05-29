<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_POST['updateUser'])) {
    if (empty($_POST["user_name"]) || empty($_POST['user_password'])) {
        $err = "Blank Values Not Accepted";
    } else {
        $Username = $_POST['user_name'];
        $Password = password_hash($_POST['user_password'], PASSWORD_DEFAULT); 
        $ID_Utente = $_GET['update'];

        $query = "UPDATE utenti SET Username = ?, Password = ? WHERE ID_Utente = ?";
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            $err = "Error preparing statement: " . $mysqli->error;
        } else {
            $stmt->bind_param('ssi', $Username, $Password, $ID_Utente);
            $stmt->execute();
            if ($stmt) {
                $success = "User Updated" && header("refresh:1; url=customes.php");
            } else {
                $err = "Please Try Again Or Try Later";
            }
        }
    }
}
require_once('partials/_head.php');
?>

<body>
    <?php require_once('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php require_once('partials/_topnav.php'); ?>
        <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;"
      class="header pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body">
        </div>
      </div>
    </div>
        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h3 class="mb-0">Please Fill All Fields</h3>
                        </div>
                        <div class="card-body">
                            <?php
                                if (isset($_GET['update'])) {
                                    $ID_Utente = $_GET['update'];
                                    $ret = "SELECT * FROM utenti WHERE ID_Utente = '$ID_Utente' ";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute(); 
                                    $res = $stmt->get_result();
                                    while ($user = $res->fetch_object()) {
                            ?>
                            <form method="POST">
                                <div class="form-group">
                                    <label for="user_name">Username</label>
                                    <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo $user->Username; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="user_password">Password</label>
                                    <input type="password" class="form-control" id="user_password" name="user_password" placeholder="Enter new password" required>
                                </div>
                                <button type="submit" name="updateUser" class="btn btn-primary">Update User</button>
                            </form>
                            <?php } } ?>
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
