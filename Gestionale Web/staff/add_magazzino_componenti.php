<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_POST['addComponente'])) {
  $ID_Componente = $_POST['ID_Componente'];
  $Quantità = $_POST['Quantità'];

  $check = "SELECT * FROM magazzino_componenti WHERE ID_Componente = ?";
  $stmt = $mysqli->prepare($check);
  $stmt->bind_param('i', $ID_Componente);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $newQuantity = $row['Quantità'] + $Quantità;
    $update = "UPDATE magazzino_componenti SET Quantità = ? WHERE ID_Componente = ?";
    $updateStmt = $mysqli->prepare($update);
    $updateStmt->bind_param('ii', $newQuantity, $ID_Componente);
    $updateStmt->execute();

    if ($updateStmt) {
      $success = "Quantità aggiornata con successo!" && header("refresh:1; url=magazzino_componenti.php");
    } else {
      $err = "Errore durante l'aggiornamento della quantità";
    }
  } else {
    $insert = "INSERT INTO magazzino_componenti (ID_Componente, Quantità) VALUES(?,?)";
    $insertStmt = $mysqli->prepare($insert);
    $insertStmt->bind_param('ii', $ID_Componente, $Quantità);
    $insertStmt->execute();

    if ($insertStmt) {
      $success = "Nuovo componente aggiunto con successo!" && header("refresh:1; url=magazzino_componenti.php");
    } else {
      $err = "Errore durante l'inserimento del nuovo componente";
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
                    <label>ID Componente</label>
                    <input type="text" name="ID_Componente" class="form-control" required>
                  </div>
                  <div class="col-md-6">
                    <label>Quantità</label>
                    <input type="text" name="Quantità" class="form-control" required>
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="addComponente" value="Aggiungi" class="btn btn-success">
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
