<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_POST['updatePurchase'])) {
    if (empty($_POST["quantita"]) || empty($_POST["costo"]) || empty($_POST['stato'])) {
        $err = "Blank Values Not Accepted";
    } else {
        $Quantità = $_POST['quantita'];
        $Costo = $_POST['costo'];
        $Stato = $_POST['stato'];
        $ID_Acquisto = $_GET['update'];

        $query = "UPDATE acquisti_fornitori SET Quantità = ?, Costo = ?, Stato = ? WHERE ID_Acquisto = ?";
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            $err = "Error preparing statement: " . $mysqli->error;
        } else {
            $stmt->bind_param('idsi', $Quantità, $Costo, $Stato, $ID_Acquisto);
            $stmt->execute();
            if ($stmt) {
                $success = "Purchase Updated" && header("refresh:1; url=order_fornitori.php"); 
            } else {
                $err = "Please Try Again Or Try Later";
            }
        }
    }
}
require_once('partials/_head.php');
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
        <?php require_once('partials/_topnav.php'); ?>
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
                                    $ID_Acquisto = $_GET['update'];
                                    $ret = "SELECT * FROM acquisti_fornitori WHERE ID_Acquisto = '$ID_Acquisto' ";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute(); 
                                    $res = $stmt->get_result();
                                    while ($purchase = $res->fetch_object()) {
                            ?>
                            <form method="POST">
                                <div class="form-group">
                                    <label for="quantita">Quantità</label>
                                    <input type="number" class="form-control" id="quantita" name="quantita" value="<?php echo $purchase->Quantità; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="costo">Costo</label>
                                    <input type="text" class="form-control" id="costo" name="costo" value="<?php echo $purchase->Costo; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="stato">Stato</label>
                                    <select class="form-control" id="stato" name="stato" required>
                                        <option value="in consegna" <?php echo ($purchase->Stato == 'in consegna' ? 'selected' : ''); ?>>In consegna</option>
                                        <option value="consegnato" <?php echo ($purchase->Stato == 'consegnato' ? 'selected' : ''); ?>>Consegnato</option>
                                    </select>
                                </div>
                                <button type="submit" name="updatePurchase" class="btn btn-primary">Aggiorna Acquisto</button>
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
