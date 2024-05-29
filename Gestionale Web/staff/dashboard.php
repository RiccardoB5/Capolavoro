<?php
session_start();
include ('config/config.php');
include ('config/checklogin.php');
check_login();

require_once ('partials/_head.php');
require_once ('partials/_analytics.php');
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
          <div class="row">
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Clienti</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $customers; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Prodotti</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $products; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                        <i class="fas fa-briefcase"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Ordini</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $orders; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                        <i class="fas fa-shopping-cart"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Totale Speso</h5>
                      <span class="h2 font-weight-bold mb-0">$<?php echo $totalSpent; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-green text-white rounded-circle shadow">
                        <i class="fas fa-dollar-sign"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
                  <h3 class="mb-0">Ordini ai Fornitori</h3>
                </div>
                <div class="col text-right">
                  <a href="order_fornitori.php" class="btn btn-sm btn-primary">See all</a>
                </div>
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
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-xl-12 mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Prodotti in Magazzino</h3>
                </div>
                <div class="col text-right">
                  <a href="magazzino_prod_finiti.php" class="btn btn-sm btn-primary">See all</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Product ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Type</th>
                    <th scope="col">Quantity</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "
                        SELECT 
                            m.ID_Prodotto_Finito,
                            m.Quantita,
                            a.Nome,
                            a.Descrizione,
                            a.Tipo
                        FROM 
                            magazzino_prodotti_finiti m
                        JOIN 
                            anagrafica_prodotti a
                        ON 
                            m.ID_Prodotto_Finito = a.ID_Prodotto
                        ORDER BY 
                            m.ID_Prodotto_Finito DESC
                        LIMIT 7";
                  $stmt = $mysqli->prepare($ret);
                  if (!$stmt) {
                    echo "Error in your query" . $mysqli->error;
                  } else {
                    $stmt->execute();
                    $res = $stmt->get_result();
                    while ($product = $res->fetch_object()) {
                      ?>
                      <tr>
                        <th scope="row">
                          <?php echo $product->ID_Prodotto_Finito; ?>
                        </th>
                        <td>
                          <?php echo $product->Nome; ?>
                        </td>
                        <td>
                          <?php echo $product->Descrizione; ?>
                        </td>
                        <td>
                          <?php echo $product->Tipo; ?>
                        </td>
                        <td>
                          <?php echo $product->Quantita; ?>
                        </td>
                      </tr>
                      <?php
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-5">
        <div class="col-xl-12 mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Componenti in Magazzino</h3>
                </div>
                <div class="col text-right">
                  <a href="magazzino_componenti.php" class="btn btn-sm btn-primary">See all</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Component ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Type</th>
                    <th scope="col">Quantity</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "
                        SELECT 
                            c.ID_Componente,
                            c.Quantità,
                            a.Nome,
                            a.Descrizione,
                            a.Tipo
                        FROM 
                            magazzino_componenti c
                        JOIN 
                            anagrafica_prodotti a
                        ON 
                            c.ID_Componente = a.ID_Prodotto
                        ORDER BY 
                            c.ID_Componente DESC
                        LIMIT 7";
                  $stmt = $mysqli->prepare($ret);
                  if (!$stmt) {
                    echo "Error in your query" . $mysqli->error;
                  } else {
                    $stmt->execute();
                    $res = $stmt->get_result();
                    while ($component = $res->fetch_object()) {
                      ?>
                      <tr>
                        <th scope="row">
                          <?php echo $component->ID_Componente; ?>
                        </th>
                        <td>
                          <?php echo $component->Nome; ?>
                        </td>
                        <td>
                          <?php echo $component->Descrizione; ?>
                        </td>
                        <td>
                          <?php echo $component->Tipo; ?>
                        </td>
                        <td>
                          <?php echo $component->Quantità; ?>
                        </td>
                      </tr>
                      <?php
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>


      <?php require_once ('partials/_footer.php'); ?>
    </div>
  </div>
  <?php
  require_once ('partials/_scripts.php');
  ?>
</body>

</html>