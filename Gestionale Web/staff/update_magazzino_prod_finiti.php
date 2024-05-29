<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_POST['updateProduct'])) {
    if (empty($_POST["product_id"]) || empty($_POST["quantity"])) {
        $err = "Blank Values Not Accepted";
    } else {
        $ID_Prodotto_Finito = $_POST['product_id'];
        $Quantita = $_POST['quantity'];

        $query = "UPDATE magazzino_prodotti_finiti SET Quantita = ? WHERE ID_Prodotto_Finito = ?";
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            $err = "Error preparing statement: " . $mysqli->error;
        } else {
            $stmt->bind_param('ii', $Quantita, $ID_Prodotto_Finito);
            $stmt->execute();
            if ($stmt) {
                $success = "Product Quantity Updated" && header("refresh:1; url=magazzino_prod_finiti.php");
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
                            <h3 class="mb-0">Update Finished Product Quantity</h3>
                        </div>
                        <div class="card-body">
                            <?php
                                if (isset($_GET['update'])) {
                                    $ID_Prodotto_Finito = $_GET['update'];
                                    $ret = "SELECT * FROM magazzino_prodotti_finiti WHERE ID_Prodotto_Finito = ?";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->bind_param('i', $ID_Prodotto_Finito);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    if ($product = $res->fetch_object()) {
                            ?>
                            <form method="POST">
                                <div class="form-group">
                                    <label for="product_id">Product ID</label>
                                    <input type="text" class="form-control" id="product_id" name="product_id" value="<?php echo $product->ID_Prodotto_Finito; ?>" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $product->Quantita; ?>" required>
                                </div>
                                <button type="submit" name="updateProduct" class="btn btn-primary">Update Quantity</button>
                            </form>
                            <?php } else { echo "<p>Product not found!</p>"; } } ?>
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
