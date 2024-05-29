<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_POST['updateProduct'])) {
    if (empty($_POST["product_name"]) || empty($_POST['product_description']) || empty($_POST['product_type'])) {
        $err = "Blank Values Not Accepted";
    } else {
        $Nome = $_POST['product_name'];
        $Descrizione = $_POST['product_description'];
        $Tipo = $_POST['product_type'];
        $ID_Prodotto = $_GET['update'];

        $query = "UPDATE anagrafica_prodotti SET Nome = ?, Descrizione = ?, Tipo = ? WHERE ID_Prodotto = ?";
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            $err = "Error preparing statement: " . $mysqli->error;
        } else {
            $stmt->bind_param('sssi', $Nome, $Descrizione, $Tipo, $ID_Prodotto);
            $stmt->execute();
            if ($stmt) {
                $success = "Product Updated" && header("refresh:1; url=prodotti.php");
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
        <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
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
                                    $ID_Prodotto = $_GET['update'];
                                    $ret = "SELECT * FROM anagrafica_prodotti WHERE ID_Prodotto = ?";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->bind_param('i', $ID_Prodotto);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($product = $res->fetch_object()) {
                            ?>
                            <form method="POST">
                                <div class="form-group">
                                    <label for="product_name">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $product->Nome; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="product_description">Product Description</label>
                                    <textarea class="form-control" id="product_description" name="product_description" required><?php echo $product->Descrizione; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="product_type">Product Type</label>
                                    <select class="form-control" id="product_type" name="product_type" required>
                                        <option value="Marmitta" <?php if ($product->Tipo == 'Marmitta') echo 'selected'; ?>>Marmitta</option>
                                        <option value="Componente" <?php if ($product->Tipo == 'Componente') echo 'selected'; ?>>Componente</option>
                                    </select>
                                </div>
                                <button type="submit" name="updateProduct" class="btn btn-primary">Update Product</button>
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
