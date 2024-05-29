<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_POST['addProduct'])) {
  $ID_Prodotto_Finito = $_POST['ID_Prodotto_Finito'];
  $Quantita = $_POST['Quantita'];

  $check = "SELECT * FROM magazzino_prodotti_finiti WHERE ID_Prodotto_Finito = ?";
  $stmt = $mysqli->prepare($check);
  $stmt->bind_param('i', $ID_Prodotto_Finito);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $newQuantita = $row['Quantita'] + $Quantita;
    $update = "UPDATE magazzino_prodotti_finiti SET Quantita = ? WHERE ID_Prodotto_Finito = ?";
    $updateStmt = $mysqli->prepare($update);
    $updateStmt->bind_param('ii', $newQuantita, $ID_Prodotto_Finito);
    $updateStmt->execute();

    if ($updateStmt) {
      $success = "Quantità del prodotto aggiornata con successo!" && header("refresh:1; url=magazzino_prod_finiti.php");
    } else {
      $err = "Errore durante l'aggiornamento della quantità del prodotto";
    }
  } else {
    $insert = "INSERT INTO magazzino_prodotti_finiti (ID_Prodotto_Finito, Quantita) VALUES(?,?)";
    $insertStmt = $mysqli->prepare($insert);
    $insertStmt->bind_param('ii', $ID_Prodotto_Finito, $Quantita);
    $insertStmt->execute();

    if ($insertStmt) {
      $success = "Nuovo prodotto finito aggiunto con successo!" && header("refresh:1; url=magazzino_prod_finiti.php");
    } else {
      $err = "Errore durante l'inserimento del nuovo prodotto finito";
    }
  }
}

require_once('partials/_head.php');
?>

<body>
  <?php require_once ('partials/_sidebar.php'); ?>
  <div class="main-content">
    <?php require_once ('partials/_topnav.php'); ?>
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
              <h3>Per favore, completa tutti i campi</h3>
            </div>
            <div class="card-body">
              <form method="POST">
                <div class="form-row">
                  <div class="col-md-6">
                    <label>ID Prodotto Finito</label>
                    <input type="text" name="ID_Prodotto_Finito" class="form-control" required>
                  </div>
                  <div class="col-md-6">
                    <label>Quantita</label>
                    <input type="text" name="Quantita" class="form-control" required>
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="addProduct" value="Aggiungi" class="btn btn-success">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php require_once ('partials/_footer.php'); ?>
  </div>
  <?php require_once ('partials/_scripts.php'); ?>
</body>
</html>
