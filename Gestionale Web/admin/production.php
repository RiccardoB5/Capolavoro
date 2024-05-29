<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $adn = "DELETE FROM magazzino_prodotti_finiti WHERE ID_Prodotto_Finito = ?";
  $stmt = $mysqli->prepare($adn);
  if ($stmt === false) {
    die("Errore nella preparazione: " . $mysqli->error);
  }
  $stmt->bind_param('i', $id);
  $executeSuccess = $stmt->execute();
  $stmt->close();
  
  if ($executeSuccess) {
    $success = "Product Deleted" && header("refresh:1; url=production.php");
  } else {
    $err = "Try Again Later";
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
    <div style="background-image: url(../admin/assets/img/theme/restro00.jpg); background-size: cover;"
      class="header  pb-8 pt-5 pt-md-8">
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
          <a href="production_exhaust.php" class="btn btn-outline-success">
            <i class="fas fa-user-plus"></i>
              Craft new Exhaust
          </a>
        </div>
            <div class="card-header border-0">
              <h3 class="mb-0">Products In Warehouse</h3>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Product ID</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT magazzino_prodotti_finiti.ID_Prodotto_Finito, anagrafica_prodotti.Nome, magazzino_prodotti_finiti.Quantita 
                          FROM magazzino_prodotti_finiti 
                          JOIN anagrafica_prodotti ON magazzino_prodotti_finiti.ID_Prodotto_Finito = anagrafica_prodotti.ID_Prodotto
                          ORDER BY magazzino_prodotti_finiti.ID_Prodotto_Finito DESC";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($product = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td><?php echo $product->ID_Prodotto_Finito; ?></td>
                      <td><?php echo $product->Nome; ?></td>
                      <td><?php echo $product->Quantita; ?></td>
                      <td>
                        <a href="production.php?delete=<?php echo $product->ID_Prodotto_Finito; ?>">
                          <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                            Delete
                          </button>
                        </a>
                        <a href="update_exhaust.php?update=<?php echo $product->ID_Prodotto_Finito; ?>">
                          <button class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                            Update
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
  <?php require_once('partials/_scripts.php'); ?>
</body>
</html>
