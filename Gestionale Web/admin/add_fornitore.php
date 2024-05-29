<?php
session_start();
include ('config/config.php');
include ('config/checklogin.php');
check_login();

if (isset($_POST['addFornitore'])) {
  if (empty($_POST["fornitore_telefono"]) || empty($_POST["fornitore_nome"]) || empty($_POST['fornitore_email']) || empty($_POST['fornitore_partitaIVA'])) {
    $err = "Valori vuoti non accettati";
  } else {
    $Nome = $_POST['fornitore_nome'];
    $Telefono = $_POST['fornitore_telefono'];
    $Email = $_POST['fornitore_email'];
    $Partita_IVA = $_POST['fornitore_partitaIVA'];

    $query = "INSERT INTO anagrafica_fornitori (Nome, Partita_IVA, Telefono, Email) VALUES(?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssss', $Nome, $Partita_IVA, $Telefono, $Email);
    $stmt->execute();
    if ($stmt) {
      $success = "Fornitore aggiunto" && header("refresh:1; url=fornitori.php");
    } else {
      $err = "Riprova o prova più tardi";
    }
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
    <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;"
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
              <h3>Per favore, completa tutti i campi</h3>
            </div>
            <div class="card-body">
              <form method="POST">
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Nome Fornitore</label>
                    <input type="text" name="fornitore_nome" class="form-control" required>
                  </div>
                  <div class="col-md-6">
                    <label>Telefono Fornitore</label>
                    <input type="text" name="fornitore_telefono" class="form-control" required>
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Email Fornitore</label>
                    <input type="email" name="fornitore_email" class="form-control" required>
                  </div>
                  <div class="col-md-6">
                    <label>Partita IVA</label>
                    <input type="text" name="fornitore_partitaIVA" class="form-control" required>
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="addFornitore" value="Aggiungi Fornitore" class="btn btn-success">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php
    require_once ('partials/_footer.php');
    ?>
  </div>
  </div>
  <?php
  require_once ('partials/_scripts.php');
  ?>
</body>

</html>