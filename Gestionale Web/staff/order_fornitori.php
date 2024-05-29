<?php
session_start();
require_once ('config/config.php');
require_once ('config/checklogin.php');
check_login();

if (isset($_GET['updateStatus'])) {
  $id = intval($_GET['updateStatus']);  

  $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
  try {
    $stmt = $mysqli->prepare("UPDATE acquisti_fornitori SET Stato = 'consegnato' WHERE ID_Acquisto = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("SELECT ID_Articolo, Quantità FROM acquisti_fornitori WHERE ID_Acquisto = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($orderDetails = $result->fetch_assoc()) {
      $stmt->close();

      $stmt = $mysqli->prepare("UPDATE magazzino_componenti SET Quantità = Quantità + ? WHERE ID_Componente = ?");
      $stmt->bind_param('ii', $orderDetails['Quantità'], $orderDetails['ID_Articolo']);
      $stmt->execute();
      $stmt->close();
    }

    $mysqli->commit();
    $_SESSION['success'] = "Order status updated and inventory adjusted successfully.";
    header("Location: order_fornitori.php");
    exit;
  } catch (Exception $e) {
    $mysqli->rollback();
    $_SESSION['error'] = "Transaction failed: " . $e->getMessage();
    header("Location: order_fornitori.php");
    exit;
  }
}

if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);  
  $query = "DELETE FROM acquisti_fornitori WHERE ID_Acquisto = ?";
  $stmt = $mysqli->prepare($query);
  if ($stmt === false) {
    die("Errore nella preparazione della query: " . $mysqli->error);
  }
  $stmt->bind_param('i', $id);
  if($stmt->execute()) {
    $success = "Deleted" && header("refresh:1; url=order_fornitori.php");
  } else {
    $err = "Try Again Later";
  }
  $stmt->close();
}


if (isset($_GET['delete'])) {
  $id = $_GET['delete'];

  $stmt = $mysqli->prepare("DELETE FROM acquisti_fornitori WHERE ID_Fornitore = ?");
  if ($stmt === false) {
    die("Errore nella preparazione: " . $mysqli->error);
  }
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->close();

  $stmt = $mysqli->prepare("DELETE FROM anagrafica_fornitori WHERE ID_Fornitore = ?");
  if ($stmt === false) {
    die("Errore nella preparazione: " . $mysqli->error);
  }
  $stmt->bind_param('i', $id);
  $executeSuccess = $stmt->execute();
  $stmt->close();

  if ($executeSuccess) {
    $success = "Deleted" && header("refresh:1; url=order_fornitori.php");
  } else {
    echo "Errore durante l'eliminazione: " . $mysqli->error;
  }
}

require_once ('partials/_head.php');  
?>

<body>
  <?php include ('partials/_sidebar.php'); ?>
  <div class="main-content">
    <?php include ('partials/_topnav.php'); ?>
    <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;"
      class="header  pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body">
        </div>
      </div>
    </div>
    <div class="container-fluid mt--7">
      <div class="row mt-5">
        <div class="col-xl-12 mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Recent Orders</h3>
                </div>
                <a href="make_order_fornitori.php" class="btn btn-outline-success">
                <i class="fas fa-user-plus"></i>
                Acquista materiale
              </a>
               
              </div>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Order Code</th>
                    <th scope="col">Supplier</th>
                    <th scope="col">Product</th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                    <th scope="col">Status</th>
                    <th scope="col">Date</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT acquisti_fornitori.ID_Acquisto, anagrafica_fornitori.Nome AS NomeFornitore, anagrafica_prodotti.Nome AS NomeProdotto, acquisti_fornitori.Costo, acquisti_fornitori.Quantità, (acquisti_fornitori.Costo * acquisti_fornitori.Quantità) AS Totale, acquisti_fornitori.Stato, acquisti_fornitori.Data_Acquisto FROM acquisti_fornitori INNER JOIN anagrafica_fornitori ON acquisti_fornitori.ID_Fornitore = anagrafica_fornitori.ID_Fornitore INNER JOIN anagrafica_prodotti ON acquisti_fornitori.ID_Articolo = anagrafica_prodotti.ID_Prodotto ORDER BY acquisti_fornitori.Data_Acquisto DESC LIMIT 7";
                  $stmt = $mysqli->prepare($ret);

                  if (!$stmt) {
                    die("Errore nella preparazione della query: " . $mysqli->error);
                  }

                  $stmt->execute();
                  $res = $stmt->get_result();

                  while ($order = $res->fetch_object()) {
                    ?>
                    <tr>
                      <td><?php echo $order->ID_Acquisto; ?></td>
                      <td><?php echo $order->NomeFornitore; ?></td>
                      <td><?php echo $order->NomeProdotto; ?></td>
                      <td>$<?php echo number_format($order->Costo, 2); ?></td>
                      <td><?php echo $order->Quantità; ?></td>
                      <td>$<?php echo number_format($order->Totale, 2); ?></td>
                      <td><?php echo $order->Stato; ?></td>
                      <td><?php echo date('d/M/Y', strtotime($order->Data_Acquisto)); ?></td>
                      <td>
                        <a href="order_fornitori.php?updateStatus=<?php echo $order->ID_Acquisto; ?>">
                          <button class="btn btn-sm btn-primary">
                            <i class="fas fa-sync-alt"></i> Update Status
                          </button>
                        </a>
                        <a href="order_fornitori.php?delete=<?php echo $order->ID_Acquisto; ?>">
                          <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                            Delete
                          </button>
                        </a>

                        <a href="update_order_fornitori.php?update=<?php echo $order->ID_Acquisto; ?>">
                          <button class="btn btn-sm btn-primary">
                            <i class="fas fa-user-edit"></i>
                            Update
                          </button>
                        </a>
                        </a>
                      </td>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <?php include ('partials/_footer.php'); ?>
    </div>
  </div>
  <?php include ('partials/_scripts.php'); ?>
</body>

</html>