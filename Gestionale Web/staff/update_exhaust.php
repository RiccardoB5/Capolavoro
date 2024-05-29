<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if(isset($_POST['updateProductFinished'])) {
    $ID_Prodotto_Finito = $_GET['update'];
    $Quantita = $_POST['quantity'];

    $query = "UPDATE magazzino_prodotti_finiti SET Quantita = ? WHERE ID_Prodotto_Finito = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        echo "Error preparing statement: " . $mysqli->error;
    } else {
        $stmt->bind_param('ii', $Quantita, $ID_Prodotto_Finito);
        $stmt->execute();
        if ($stmt) {
            $success = "Quantity Changed" && header("refresh:1; url=production.php"); 
        } else {
            $err = "Errore: " . $e->getMessage();
        }
    }
}

if(isset($_GET['update'])) {
    $ID_Prodotto_Finito = $_GET['update'];
    $ret = "SELECT magazzino_prodotti_finiti.ID_Prodotto_Finito, magazzino_prodotti_finiti.Quantita, anagrafica_prodotti.Nome FROM magazzino_prodotti_finiti JOIN anagrafica_prodotti ON magazzino_prodotti_finiti.ID_Prodotto_Finito=anagrafica_prodotti.ID_Prodotto WHERE magazzino_prodotti_finiti.ID_Prodotto_Finito = '$ID_Prodotto_Finito'";
    $stmt= $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
    $prod = $res->fetch_object();
}

require_once('partials/_head.php');
?>

?>

<body>
<?php require_once('partials/_sidebar.php'); ?>
  <div class="main-content">
    <?php require_once('partials/_topnav.php'); ?>
    <div style="background-image: url(assets/img/theme/product.jpg); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
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
                        <h3 class="mb-0">Modifica Quantità Prodotto</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label>Nome Prodotto</label>
                                <input type="text" class="form-control" value="<?php echo $prod->Nome; ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label>Quantità</label>
                                <input type="number" name="quantity" class="form-control" value="<?php echo $prod->Quantita; ?>" required>
                            </div>
                            <button type="submit" name="updateProductFinished" class="btn btn-primary">Aggiorna Quantità</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once('partials/_footer.php'); ?>
    </div>
    <?php require_once('partials/_scripts.php'); ?>
</body>
</html>
