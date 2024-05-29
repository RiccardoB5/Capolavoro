<?php
session_start();
include ('config/config.php');
include ('config/checklogin.php');
check_login();

if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $adn = "DELETE FROM anagrafica_prodotti WHERE ID_Prodotto = ?";
  $stmt = $mysqli->prepare($adn);
  if ($stmt === false) {
    die("Errore nella preparazione: " . $mysqli->error);
  }
  $stmt->bind_param('i', $id);
  $executeSuccess = $stmt->execute();
  $stmt->close();

  if ($executeSuccess) {
    $success = "Deleted" && header("refresh:1; url=prodotti.php");
  } else {
    $err = "Try Again Later";
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
    <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
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
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Product Management</h3>
                </div>
                <div class="col text-left">
                  <a href="add_product.php" class="btn btn-outline-success">
                    <i class="fas fa-plus"></i> Add New Product
                  </a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Descrizione</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT ID_Prodotto, Nome, Descrizione, Tipo FROM anagrafica_prodotti ORDER BY ID_Prodotto DESC";
                  $stmt = $mysqli->prepare($ret);
                  if ($stmt === false) {
                    die("Errore nella preparazione della query: " . $mysqli->error);
                  }
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($product = $res->fetch_object()) {
                    ?>
                    <tr>
                      <td><?php echo $product->ID_Prodotto; ?></td>
                      <td><?php echo $product->Nome; ?></td>
                      <td><?php echo $product->Descrizione; ?></td>
                      <td><?php echo $product->Tipo; ?></td>
                      <td>
                        <a href="prodotti.php?delete=<?php echo $product->ID_Prodotto; ?>">
                          <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Delete
                          </button>
                        </a>
                        <a href="update_product.php?update=<?php echo $product->ID_Prodotto; ?>">
                          <button class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Update
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
    <?php
    require_once ('partials/_footer.php');
    ?>
  </div>
  <?php
  require_once ('partials/_scripts.php');
  ?>
</body>
</html>
