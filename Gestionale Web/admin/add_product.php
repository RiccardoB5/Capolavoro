<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_POST['addProduct'])) {
  if (empty($_POST["product_name"]) || empty($_POST['product_description']) || empty($_POST['product_type'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $Nome = $_POST['product_name'];
    $Descrizione = $_POST['product_description'];
    $Tipo = $_POST['product_type'];
    $query = "INSERT INTO anagrafica_prodotti (Nome, Descrizione, Tipo) VALUES(?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sss', $Nome, $Descrizione, $Tipo);
    $stmt->execute();
    if ($stmt) {
      $success = "Product Added" && header("refresh:1; url=prodotti.php");
    } else {
      $err = "Please Try Again Or Try Later";
    }
  }
}
require_once('partials/_head.php');
?>

<body>
  <?php
  require_once('partials/_sidebar.php');
  ?>
  <div class="main-content">
    <?php
    require_once('partials/_topnav.php');
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
              <h3>Please Fill All Fields</h3>
            </div>
            <div class="card-body">
              <form method="POST">
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Product Name</label>
                    <input type="text" name="product_name" class="form-control">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Product Description</label>
                    <textarea name="product_description" class="form-control"></textarea>
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Product Type</label>
                    <select name="product_type" class="form-control">
                      <option value="Marmitta">Marmitta</option>
                      <option value="Componente">Componente</option>
                    </select>
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="addProduct" value="Add Product" class="btn btn-success">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php
      require_once('partials/_footer.php');
      ?>
    </div>
  </div>
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>

</html>
