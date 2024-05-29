<?php
session_start();
include ('config/config.php');
include ('config/checklogin.php');
check_login();

if (isset($_GET['delete_cart'])) {
  $id_cart = $_GET['delete_cart'];
  $adn = "DELETE FROM carrello WHERE ID_Carrello = ?";
  $stmt = $mysqli->prepare($adn);
  if ($stmt === false) {
    die("Errore nella preparazione: " . $mysqli->error);
  }
  $stmt->bind_param('i', $id_cart);
  $executeSuccess = $stmt->execute();
  $stmt->close();

  if ($executeSuccess) {
    $success = "Deleted" && header("refresh:1; url=order_customes.php");
  } else {
    $err = "Try Again Later";
  }
}

$searchID = '';
if (isset($_POST['search'])) {
  $searchID = $_POST['search_id'];
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
      class="header pb-8 pt-5 pt-md-8">
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
              <form method="POST" class="form-inline">
                <div class="form-group mb-2">
                  <label for="search_id" class="sr-only">Search by Client ID</label>
                  <input type="text" class="form-control" id="search_id" name="search_id" placeholder="Search by Client ID" value="<?php echo htmlspecialchars($searchID); ?>">
                </div>
                <button type="submit" name="search" class="btn btn-primary mb-2">Search</button>
                <a href="order_customes.php" class="btn btn-secondary mb-2 ml-2">Show All</a>
                <a href="add_cart_item.php" class="btn btn-outline-success ml-2">
                  <i class="fas fa-plus"></i>
                  Add New Cart Item
                </a>
              </form>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">ID Cliente</th>
                    <th scope="col">Nome Cliente</th>
                    <th scope="col">ID Prodotto</th>
                    <th scope="col">Quantità</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if ($searchID) {
                    $ret = "SELECT carrello.*, utenti.Username FROM carrello JOIN utenti ON carrello.ID_Utente = utenti.ID_Utente WHERE carrello.ID_Utente = ?";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->bind_param('i', $searchID);
                  } else {
                    $ret = "SELECT carrello.*, utenti.Username FROM carrello JOIN utenti ON carrello.ID_Utente = utenti.ID_Utente ORDER BY carrello.ID_Carrello DESC";
                    $stmt = $mysqli->prepare($ret);
                  }
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($cart = $res->fetch_object()) {
                    ?>
                    <tr>
                      <td><?php echo $cart->ID_Utente; ?></td>
                      <td><?php echo $cart->Username; ?></td>
                      <td><?php echo $cart->ID_Prodotto; ?></td>
                      <td><?php echo $cart->Quantita; ?></td>
                      <td>
                        <a href="order_customes.php?delete_cart=<?php echo $cart->ID_Carrello; ?>">
                          <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                            Delete
                          </button>
                        </a>
                        <a href="update_order_customer.php?update_cart=<?php echo $cart->ID_Carrello; ?>">
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
    <!-- Footer -->
    <?php
    require_once ('partials/_footer.php');
    ?>
  </div>
  </div>
  <!-- Argon Scripts -->
  <?php
  require_once ('partials/_scripts.php');
  ?>
</body>

</html>
