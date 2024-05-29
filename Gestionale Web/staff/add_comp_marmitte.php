<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

$componentQuery = "SELECT * FROM anagrafica_prodotti WHERE Tipo = 'Componente'";
$componentStmt = $mysqli->prepare($componentQuery);
$componentStmt->execute();
$componentResult = $componentStmt->get_result();

$mufflerQuery = "SELECT * FROM anagrafica_prodotti WHERE Tipo = 'Marmitta'";
$mufflerStmt = $mysqli->prepare($mufflerQuery);
$mufflerStmt->execute();
$mufflerResult = $mufflerStmt->get_result();

if (isset($_POST['addMarmitta'])) {
    $ID_Marmitta = $_POST['ID_Marmitta'];
    $ID_Componente1 = $_POST['ID_Componente1'];
    $Quantità_Componente1 = $_POST['Quantità_Componente1'];
    $ID_Componente2 = $_POST['ID_Componente2'];
    $Quantità_Componente2 = $_POST['Quantità_Componente2'];
    $ID_Componente3 = $_POST['ID_Componente3'];
    $Quantità_Componente3 = $_POST['Quantità_Componente3'];

    $query = "INSERT INTO composizione_marmitte (ID_Marmitta, ID_Componente1, Quantità_Componente1, ID_Componente2, Quantità_Componente2, ID_Componente3, Quantità_Componente3) VALUES(?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        $stmt->bind_param('iiiiiii', $ID_Marmitta, $ID_Componente1, $Quantità_Componente1, $ID_Componente2, $Quantità_Componente2, $ID_Componente3, $Quantità_Componente3);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Marmitta aggiunta con successo');</script>";
            header("refresh:1; url=comp_marmitte.php");
        } else {
            echo "<script>alert('Errore durante l'inserimento');</script>";
        }
    } else {
        $err = "Errore nella preparazione: " . $mysqli->error;
    }
    $stmt->close();
}
require_once('partials/_head.php');
?>

<body>
    <?php include('partials/_sidebar.php'); ?>
    <div class="main-content">
        <?php include('partials/_topnav.php'); ?>
        <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;"
            class="header pb-8 pt-5 pt-md-8">
            <span class="mask bg-gradient-dark opacity-8"></span>
            <div class="container-fluid">
                <div class="header-body">
                </div>
            </div>
        </div>
        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col-xl-12 order-xl-1">
                    <div class="card bg-secondary shadow">
                        <div class="card-header bg-white border-0">
                            <h3 class="mb-0">Aggiungi Marmitta</h3>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <h6 class="heading-small text-muted mb-4">Informazioni Marmitta</h6>
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="ID_Marmitta">Marmitta</label>
                                                <select class="form-control" id="ID_Marmitta" name="ID_Marmitta" required>
                                                    <?php while ($muffler = $mufflerResult->fetch_assoc()): ?>
                                                        <option value="<?php echo $muffler['ID_Prodotto']; ?>">
                                                            <?php echo $muffler['Nome']; ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="ID_Componente1">Componente 1</label>
                                                <select class="form-control" id="ID_Componente1" name="ID_Componente1" required>
                                                    <?php while ($component = $componentResult->fetch_assoc()): ?>
                                                        <option value="<?php echo $component['ID_Prodotto']; ?>">
                                                            <?php echo $component['Nome']; ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="Quantità_Componente1">Quantità Componente 1</label>
                                                <input type="number" id="Quantità_Componente1" name="Quantità_Componente1" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="ID_Componente2">Componente 2</label>
                                                <select class="form-control" id="ID_Componente2" name="ID_Componente2" required>
                                                    <?php 
                                                    $componentStmt->execute(); 
                                                    $componentResult = $componentStmt->get_result();
                                                    while ($component = $componentResult->fetch_assoc()): ?>
                                                        <option value="<?php echo $component['ID_Prodotto']; ?>">
                                                            <?php echo $component['Nome']; ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="Quantità_Componente2">Quantità Componente 2</label>
                                                <input type="number" id="Quantità_Componente2" name="Quantità_Componente2" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="ID_Componente3">Componente 3</label>
                                                <select class="form-control" id="ID_Componente3" name="ID_Componente3" required>
                                                    <?php 
                                                    $componentStmt->execute(); 
                                                    $componentResult = $componentStmt-> get_result();
                                                    while ($component = $componentResult->fetch_assoc()): ?>
                                                        <option value="<?php echo $component['ID_Prodotto']; ?>">
                                                            <?php echo $component['Nome']; ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label" for="Quantità_Componente3">Quantità Componente 3</label>
                                                <input type="number" id="Quantità_Componente3" name="Quantità_Componente3" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="submit" name="addMarmitta" value="Aggiungi" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('partials/_footer.php'); ?>
    </div>
    <?php include('partials/_scripts.php'); ?>
</body>
</html>
