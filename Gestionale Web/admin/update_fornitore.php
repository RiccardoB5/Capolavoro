<?php
session_start();
include ('config/config.php');
include ('config/checklogin.php');
check_login();

if (isset($_POST['updateSupplier'])) {
    if (empty($_POST["supplier_phoneno"]) || empty($_POST["supplier_name"]) || empty($_POST['supplier_email']) || empty($_POST['supplier_partitaIVA'])) {
        $err = "Blank Values Not Accepted";
    } else {
        $Nome = $_POST['supplier_name'];
        $Telefono = $_POST['supplier_phoneno'];
        $Email = $_POST['supplier_email'];
        $PartitaIVA = $_POST['supplier_partitaIVA'];
        $ID_Fornitore = $_GET['update'];

        $query = "UPDATE anagrafica_fornitori SET Nome = ?, Telefono = ?, Email = ?, Partita_IVA = ? WHERE ID_Fornitore = ?";
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            $err = "Error preparing statement: " . $mysqli->error;
        } else {
            $stmt->bind_param('ssssi', $Nome, $Telefono, $Email, $PartitaIVA, $ID_Fornitore);
            $stmt->execute();
            if ($stmt) {
                $success = "Supplier Updated" && header("refresh:1; url=fornitori.php");
            } else {
                $err = "Please Try Again Or Try Later";
            }
        }
    }
}

require_once ('partials/_head.php');
?>

<body>
    <?php
    require_once ('partials/_sidebar.php');
    ?>
    <div class="main-content">
        <?php
        require_once ('partials/_topnav.php');
        ?>
        <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;"
            class="header  pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body">
                </div>
            </div>
        </div>
        <div class="main-content">
            <?php require_once ('partials/_topnav.php'); ?>
            <div class="container-fluid mt--7">
                <div class="row">
                    <div class="col">
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <h3 class="mb-0">Supplier Information</h3>
                            </div>
                            <div class="card-body">
                                <?php
                                if (isset($_GET['update'])) {
                                    $ID_Fornitore = $_GET['update'];
                                    $ret = "SELECT * FROM anagrafica_fornitori WHERE ID_Fornitore = '$ID_Fornitore' ";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($forn = $res->fetch_object()) {
                                        ?>
                                        <form method="POST">
                                            <div class="form-group">
                                                <label for="supplier_name">Supplier Name</label>
                                                <input type="text" class="form-control" id="supplier_name" name="supplier_name"
                                                    value="<?php echo $forn->Nome; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="supplier_phoneno">Phone Number</label>
                                                <input type="text" class="form-control" id="supplier_phoneno"
                                                    name="supplier_phoneno" value="<?php echo $forn->Telefono; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="supplier_email">Email</label>
                                                <input type="email" class="form-control" id="supplier_email"
                                                    name="supplier_email" value="<?php echo $forn->Email; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="supplier_partitaIVA">Partita IVA</label>
                                                <input type="text" class="form-control" id="supplier_partitaIVA"
                                                    name="supplier_partitaIVA" value="<?php echo $forn->Partita_IVA; ?>"
                                                    required>
                                            </div>
                                            <button type="submit" name="updateSupplier" class="btn btn-primary">Update
                                                Supplier</button>
                                        </form>
                                    <?php }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php require_once ('partials/_footer.php'); ?>
            </div>
        </div>
        <?php require_once ('partials/_scripts.php'); ?>
</body>

</html>